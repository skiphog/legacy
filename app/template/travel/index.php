<?php
/**
 * @var \Swing\System\Controller $this
 */

$tc = $this->myrow->isUser() ? $this->myrow->id : 'NULL';
$this->dbh->exec('insert into travel_count (user_id) values ('. $tc .')');

$select_date = $this->dbh->quote(date('Y-m-d'));

$sql = 'select tu.id,tu.user_id, tu.is_children, tu.payment, tu.description,tu.date_start,tu.date_end,tu.sgender,tu.transport,
  u.login,u.gender,u.pic1 avatar, u.fname,u.city user_city,u.birthday,u.real_status,u.photo_visibility,
  tc.title country_title,
  tci.title city_title,tci.center
from travel_users tu
  join users u on u.id = tu.user_id
  join travel_country tc on tc.id = tu.country_id
  join travel_city tci on tci.id = tu.city_id
where tu.date_end >= ' . $select_date . '
order by tu.id desc';

$sth = $this->dbh->query($sql);

if ($sth->rowCount()) {

    require __PATH__ . '/ajax/system/ArrayTravel.php';

    $travel = [
        'map' => [
            'type'     => 'FeatureCollection',
            'features' => []
        ],
        'date' => []
    ];

    $date_now = (new DateTime())->modify('first day of')->setTime(0, 0, 0);
    while ($row = $sth->fetch()) {

        $sgender = explode(',', $row['sgender']);

        array_walk($sgender, function (&$v) {
            $v = \Swing\Arrays\Genders::$sgender[$v];
        });

        $travel['map']['features'][] = [
            'type'       => 'Feature',
            'id'         => (int)$row['id'],
            'geometry'   => [
                'type'        => 'Point',
                'coordinates' => json_decode($row['center'])
            ],
            'properties' => [
                'user_id'        => $row['user_id'],
                'destination'    => $row['country_title'] . ', ' . $row['city_title'],
                'datetime'       => 'c <strong>' . date('d-m-Y', strtotime($row['date_start'])) . '</strong> по <strong>' . date('d-m-Y', strtotime($row['date_end'])) . '</strong>',
                'avatar'         => avatar($this->myrow, $row['avatar'], $row['photo_visibility']),
                'real'           => empty($row['real_status']) ? '' : '<img src="/img/real.gif" width="14" height="14" alt="Real">',
                'info'           => '<a class="hover-tip" href="/id' . $row['user_id'] . '" target="_blank"><img src="/img/info_small_' . $row['gender'] . '.png" width="15" height="14" alt="gender"> ' . $row['login'] . '</a>',
                'name'           => html($row['fname']) . ', ' . (new \Swing\Components\SwingDate($row['birthday']))->getHumansShort(),
                'city'           => html($row['user_city']),
                'transport'      => '<img src="/img/travel/travel_' . $row['transport'] . '.png" width="32" height="32" title="' . ArrayTravel::$transport[$row['transport']] . '" alt="transport" >',
                'children'       => ArrayTravel::$case['child'][$row['gender']] . ' ' . ArrayTravel::$is_children[$row['is_children']],
                'payment'        => ArrayTravel::$payment[$row['payment']],
                'sgender'        => ArrayTravel::$case['search'][$row['gender']] . ' ' . implode(', ', $sgender),
                'description'    => nl2br(html($row['description'])),
                'clusterCaption' => $row['login'],
                'del'            => $this->myrow->isModerator() || ($this->myrow->id === $row['user_id']) ? '<span class="del-travel" title="Удалить" data-value="'.$row['id'].'">Удалить</span>':''
            ]
        ];

        $d1 = (new DateTime($row['date_start']))->modify('first day of');
        $d2 = new DateTime($row['date_end']);

        do {
            if ($d1 >= $date_now) {
                $travel['date'][$d1->format('Y-m-d')] = ArrayTravel::$moth[$d1->format('n')] . ' ' . $d1->format('Y');
            }
        } while ($d1->modify('+1 month') < $d2);
    }

    ksort($travel['date']);

    // Получаем пол
    $sql = 'select u.gender, count(u.gender) cnt
      from travel_users tu
        join users u on u.id = tu.user_id
      where tu.date_end >= ' . $select_date . '
    group by u.gender order by gender';
    $sth = $this->dbh->query($sql);

    $travel['gender'] = [];
    while ($row = $sth->fetch()) {
        $row['title'] = \Swing\Arrays\Genders::$sgender[$row['gender']];
        $travel['gender'][] = $row;
    }

    // Получаем страны
    $sql = 'select c.id,c.title, count(c.id) cnt
      from travel_users tu
        join travel_country c on c.id = tu.country_id
      where tu.date_end >= ' . $select_date . '
      group by c.id order by  cnt desc ';
    $sth = $this->dbh->query($sql);

    $travel['country'] = [];
    while ($row = $sth->fetch()) {
        $travel['country'][] = $row;
    }

    $travel['all_users'] = array_sum(array_column($travel['country'], 'cnt'));
}?>

<?php
/**
 * @var $travel array
 */
?>
<style>
    .loading{position:relative;cursor:default;text-shadow:none !important;color:transparent !important;opacity:1;pointer-events:auto;-webkit-transition:all 0s linear,opacity .1s ease;transition:all 0s linear,opacity .1s ease}
    .loading:before{position:absolute;content:'';top:40%;left:50%;margin:-.64285714em 0 0 -.64285714em;width:1.28571429em;height:1.28571429em;border-radius:500rem;border:.2em solid rgba(0,0,0,.15)}
    .loading:after{position:absolute;content:'';top:40%;left:50%;margin:-.64285714em 0 0 -.64285714em;width:1.28571429em;height:1.28571429em;-webkit-animation:button-spin .6s linear;animation:button-spin .6s linear;-webkit-animation-iteration-count:infinite;animation-iteration-count:infinite;border-radius:500rem;border:.2em solid transparent;border-top-color:#FFF;box-shadow:0 0 0 1px transparent}
    @-webkit-keyframes "button-spin"{from{-webkit-transform:rotate(0deg);transform:rotate(0deg);}to{-webkit-transform:rotate(360deg);transform:rotate(360deg);}}
    @keyframes "button-spin"{from{-webkit-transform:rotate(0deg);transform:rotate(0deg);}to{-webkit-transform:rotate(360deg);transform:rotate(360deg);}}
    .travel div{border-radius:4px;}
    .head-travel>div,.t_body>div,.table-cell{display:table-cell;vertical-align:top;}
    .t-options,.travel-div>div{padding-right:5px;}
    #travel-map{width:100%;height:100%;padding:0;margin:0;background:url("/img/load_green.gif") no-repeat center;}
    #travel-form{padding:5px 30px;background-color:#ebebf3;}
    .t-map{width: <?= !empty($_SESSION['mobile']) ? '580px': '100%' ?>;height:370px;padding:0;}
    .t-h3,.t-time{margin:0;}
    .t-time,.travel-select>label,.tr-desc{font-size:0.96em;}
    .t-time{color:#737d82;}
    .travel-select>label{display:block;font-weight:bold;color:#3c3c3c;}
    .travel-select>select{width:150px;padding:3px;}
    .travel-select{margin-bottom:10px;}
    .t-ava{padding-right:5px;}
    .t-info{line-height:1.35;}
    .trave-button{margin:30px 0 10px;text-align:center;}
    .travel-div{display:inline-block;height:225px;overflow:hidden;width:calc(50% - 30px);margin:0 0 10px 10px;}
    .travel-overflow{position:absolute;background:#fff;width:35%;}
    .travel-div img{padding:0;}
    .tr-div{width:100%;}
    .travel hr{border-style:ridge;margin:5px 0;}
    .tr-desc{color:#515252;font-style:italic;cursor:pointer;}
    .tr-desc:hover{color:#0000FF;}
    .t-visible{visibility:hidden;}
    .clearfix{clear:both;}
    .add-travel{margin:10px 15px 0 0;float:right;}
    .tr-transport{float:left;margin:0 15px;}
    .mask{opacity:0.4;}
    .travel-img{text-align:center;}
    .del-travel{color:#CCC;float:right;cursor:pointer;padding:2px;}
    .del-travel:hover{color:#D21B1B;}
    .more-info{display:block;width:150px;}
</style>

<div class="travel">
    <h1>Cвинг в путешествии</h1>

    <?php if(empty($travel)) :?>
        <a href="/add_travel" class="btn btn-success" rel="nofollow">Добавить объявление</a>
    <?php else :?>

    <?php if(!empty($_SESSION['travel'])) :?>
        <div style="color: #1F7900;font-size: 16px;font-weight: bold">Ваше объявление добавлено</div>
        <?php unset($_SESSION['travel']) ?>
    <?php endif; ?>
    <hr>
    <div class="head-travel">
        <div id="t-opt" class="t-options t-visible">
            <form id="travel-form" class="border-box">
                <div class="travel-img">
                    <img src="/img/travel/travel_all.png" alt="travel" width="124" height="100">
                </div>
                <div class="travel-select">
                    <label for="travel_time">Время поездки</label>
                    <select name="travel_time" id="travel_time">
                        <option value="0" selected>Любое</option>
                        <?php foreach ((array)$travel['date'] as $key => $item) {?>
                            <option value="<?= $key; ?>"><?= $item; ?></option>
                        <?php }?>
                    </select>
                </div>

                <div class="travel-select">
                    <label for="gender">Кого ищу</label>
                    <select name="gender" id="gender">
                        <option value="0" selected>Всех (<?= $travel['all_users']; ?>)</option>
                        <?php foreach ((array)$travel['gender'] as $item) {?>
                            <option value="<?= $item['gender']; ?>"><?= $item['title']; ?> (<?= $item['cnt']; ?>)</option>
                        <?php }?>
                    </select>
                </div>


                <div class="travel-select">
                    <label for="country_id">Страна</label>
                    <select name="country_id" id="country_id">
                        <option value="0" selected>Все (<?= $travel['all_users']; ?>)</option>
                        <?php foreach ((array)$travel['country'] as $item) {?>
                            <option value="<?= $item['id']; ?>"><?= $item['title']; ?> (<?= $item['cnt']; ?>)</option>
                        <?php }?>
                    </select>
                </div>

                <div class="travel-select">
                    <label for="city_id">Город</label>
                    <select name="city_id" id="city_id">
                        <option value="0" selected>Все (<?= $travel['all_users']; ?>)</option>
                    </select>
                </div>

                <div class="trave-button">
                    <button id="submit-travel" type="submit" class="btn btn-primary">Показать</button>
                </div>

            </form>
        </div>
        <div class="t-map border-box">
            <div id="travel-map"></div>
        </div>
    </div>

    <div class="clearfix">
        <div class="add-travel">
            <a href="/add_travel" class="btn btn-success" rel="nofollow">Добавить объявление</a>
        </div>
    </div>

    <div id="response"></div>
</div>


    <script src="//api-maps.yandex.ru/2.1/?lang=ru_RU"></script>
    <script>
      var travel = <?= json_encode($travel, JSON_UNESCAPED_UNICODE); ?>;
      var sts = true, ft, resp = $('#response'),usr;

      ymaps.ready(function () {

        var time=$('#travel_time'),gender=$('#gender'),country=$('#country_id'),city=$('#city_id'),st=$('#submit-travel'),sel=$('select'),resp=$('#response');
        $('#t-opt').removeClass('t-visible');

        ft = {
          gender: gender.html(),
          country: country.html(),
          city: city.html()
        };

        var myMap = new ymaps.Map('travel-map', {
            center: [55.76, 37.64],
            zoom: 5,
            controls: ['zoomControl', 'typeSelector',  'fullscreenControl']
          }),

          customItemContentLayout = ymaps.templateLayoutFactory.createClass(
            '<div class="travel-info">' +
            '<div class=t_body>' +
            '<div class="t-ava"><a href="/id{{ properties.user_id }}" target="_blank"><img src="{{ properties.avatar }}" width="50" height="50" alt="avatar"></a></div>' +
            '<div class="t-info"><div>{{ properties.real|raw }} {{ properties.info|raw }}</div><div>{{ properties.name }}</div><div>{{ properties.city }}</div></div>' +
            '</div>' +
            '<div class="clearfix">' +
            '<div><div class="tr-transport">{{ properties.transport|raw }}</div></div>' +
            '<div><h3 class="t-h3">{{ properties.destination }}</h3>' +
            '<div class="t-time">{{ properties.datetime|raw }}</div></div>' +
            '</div>'+
            '<hr>' +
            '<ul><li>{{ properties.sgender }}</li><li>{{ properties.children }}</li><li>{{ properties.payment }}</li></ul>' +
            '<hr>' +
            '<div class=t_footer>{{ properties.description|raw }}</div>' +
            '</div>'
          ),

          objectManager = new ymaps.ObjectManager({
            clusterize: true,
            clusterDisableClickZoom: true,
            clusterHideIconOnBalloonOpen: false,
            clusterBalloonItemContentLayout: customItemContentLayout,
            preset: 'islands#invertedVioletClusterIcons',

            geoObjectHideIconOnBalloonOpen: false,
            geoObjectBalloonContentLayout: customItemContentLayout,
            geoObjectBalloonMinWidth: 350,
            geoObjectIconLayout: 'default#image',
            geoObjectIconImageHref: '/img/lapas2.png',
            geoObjectIconImageSize: [20, 20],
            iconImageOffset: [-10, -10]

          });

        myMap.geoObjects.add(objectManager);
        objectManager.add(travel.map);
        myMap.setBounds(objectManager.getBounds(), {
          checkZoomRange: true,
          zoomMargin: 1
        });

        setResponseUsers(travel.map.features);

        time.on('change',function () {
          var data = {
            date: $(this).val()
          };
          if(parseInt(data.date) === 0) {
            return setFirstTemplate(ft);
          }
          selectData('getTravelDate', data, setFirstTemplate);
        });

        gender.on('change', function () {
          var data = {
            g: parseInt($(this).val()),
            d: time.val()
          };
          selectData('getTravelGender', data, function (j) {
            country.html(j.country);
            city.html(j.city);
          });
        });

        country.on('change', function () {
          var data = {
            c: parseInt($(this).val()),
            g: parseInt(gender.val()),
            d: time.val()
          };
          selectData('getTravelCountry', data, function (j) {
            city.html(j.city);
          });
        });

        function selectData(action, data, callback) {
          if(!sts){return;}
          $.ajax({
            url: '/ajax?cntr=Travel&action=' + action,
            data : data,
            beforeSend: function () {
              changeSend();
              st.addClass('loading');
              sel.attr('disabled','disabled');
            },
            success: function (j) {
              callback(j);
            },
            complete: function () {
              changeSend();
              st.removeClass('loading');
              sel.removeAttr('disabled');
            },
            error:function () {
              setFirstTemplate(ft);
            }
          });
        }

        function setResponseUsers(e) {
          var u = ['<h2>Найдено анкет: '+ e.length +'</h2>'];
          if(e.length > 30) {
            var o = e.splice(0,30);
            usr=e;
            return sliceUsers(u, o, true);
          }
          sliceUsers(u,e, false);
        }

        function sliceUsers(u, e, d) {
          $(e).each(function (i, o) {
            var el = o.properties;
            var t = '<div class="border-box travel-div">' + el.del +
              '<div class="table-cell"><a href="/id'+ el.user_id +'" target="_blank"><img class="border-box" src="'+ el.avatar +'" width="50" height="50" alt="avatar"></a></div>' +
              '<div class="table-cell tr-div"><div>'+ el.real +' '+ el.info +'</div><div> '+ el.name +'</div><div>'+ el.city +'</div></div>' +
              '<div class="clearfix"><div class="tr-transport">'+ el.transport +'</div>' +
              '<div><h3 class="t-h3">'+ el.destination +'</h3><div class="t-time">'+ el.datetime +'</div></div></div><hr>'+
              '<ul><li>'+ el.sgender +'</li><li>'+ el.children +'</li><li>'+ el.payment +'</li></ul><hr>' +
              '<div class="tr-desc">'+ el.description +'</div>' +
              '</div>';
            u.push(t);
          });
          resp.append(u.join(' '));
          if(d) {
            addButtonMore();
          }
        }

        function addButtonMore() {
          var $more = $('<button/>').addClass('more-info btn btn-success').html('Показать еще').appendTo(resp);
          $more.on('click',function() {
            $more.remove();
            if(usr.length > 30) {
              var o = usr.splice(0,30);
              return sliceUsers([], o, true);
            }
            sliceUsers([],usr, false);
          });
        }

        $('#travel-form').on('submit',function (e) {
          e.preventDefault();
          if(!sts){return;}
          var data = $(this).serialize();

          $.ajax({
            type: 'post',
            url:'/ajax/',
            dataType: 'json',
            data : data + '&cntr=GlobalTravel&action=searchTravel',
            beforeSend : function () {
              resp.addClass('mask');
              changeSend();
              st.addClass('loading');
              objectManager.removeAll();
            },
            success: function (j) {
              objectManager.add(j.map);
              var bounds = j.bounds || objectManager.getBounds();
              myMap.setBounds(bounds, {
                checkZoomRange: true,
                duration: 200,
                zoomMargin: 1
              });
              resp.html('');
              setResponseUsers(j.map.features);
            },
            error: function () {
              alert('Forbidden 403');
            },
            complete: function () {
              changeSend();
              st.removeClass('loading');
              resp.removeClass('mask');
            }
          });
        });

        resp.on('click','.del-travel',function () {
          if(confirm('Удалить объявление?') === false) {
            return;
          }
          var that = $(this);
          var id = parseInt(that.data('value')) || 0;
          if(!id){return;}
          that.parent().html('Объявление удалено');
          $.post('/ajax/',{cntr:'Travels',action:'delTravel',id:id});
        });

        function setFirstTemplate(j) {
          gender.html(j.gender);
          country.html(j.country);
          city.html(j.city);
        }
      });

      resp.on('click','.tr-desc', function () {
        $(this).toggleClass('travel-overflow border-box');
      });

      function changeSend(){
        sts = !sts;
      }
    </script>
<?php endif; ?>


