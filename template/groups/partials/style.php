<style>
    #response{margin-top:20px}
    #loadsa{text-align:center;margin-bottom:-40px}
    .fixed{position:fixed;top:2px}
    .spinner{background-color:#2E8CE3;-webkit-animation:rotateplane 1.2s infinite ease-in-out;animation:rotateplane 1.2s infinite ease-in-out}
    @-webkit-keyframes rotateplane {
        0%{-webkit-transform:perspective(120px)}
        50%{-webkit-transform:perspective(120px) rotateY(180deg)}
        100%{-webkit-transform:perspective(120px) rotateY(180deg) rotateX(180deg)}
    }
    @keyframes rotateplane {
        0%{transform:perspective(120px) rotateX(0deg) rotateY(0deg);-webkit-transform:perspective(120px) rotateX(0deg) rotateY(0deg)}
        50%{transform:perspective(120px) rotateX(-180.1deg) rotateY(0deg);-webkit-transform:perspective(120px) rotateX(-180.1deg) rotateY(0deg)}
        100%{transform:perspective(120px) rotateX(-180deg) rotateY(-179.9deg);-webkit-transform:perspective(120px) rotateX(-180deg) rotateY(-179.9deg)}
    }
    section a,.scrolltop{-webkit-transition:all .3s;-moz-transition:all .3s;transition:all .3s}
    section a{text-decoration:none;color:#11638C}
    section a:hover{color:#00F}
    section label{font-size:1.5em;font-weight:700}
    .scrolltop{cursor:pointer;opacity:.5;background:#2E8CE3;width:60px;text-align:center;bottom:-30px;height:15px;padding:5px;left:50%}
    .scrolltop:hover,.scrop{opacity:1;background:#24b662}
    .borderkis,.friend-foto{border:1px solid #CCC;box-shadow:0 1px 5px #CCC;-moz-box-shadow:0 1px 5px #CCC;-webkit-border-shadow:0 1px 5px #CCC}
    .padkis,div.padkis table{padding:20px;margin-bottom:15px}
    div.padkissmall{padding:10px;margin-bottom:5px}
    div.padkissmall > a{font-weight:700;text-transform:uppercase}
    div.padkissmall span{display:block;margin-left:15px}
    div.padkis table{background-color:#f4f4f4;border:1px solid #d1d1d1;box-shadow:0 1px 5px #d1d1d1;-moz-box-shadow:0 1px 5px #d1d1d1;-webkit-border-shadow:0 1px 5px #d1d1d1}
    .red-border{border:1px solid #FF8B8B!important;box-shadow:0 1px 5px #FF8B8B!important;-moz-box-shadow:0 1px 5px #FF8B8B!important;-webkit-border-shadow:0 1px 5px #FF8B8B!important}
    .group-new-thread{background-color:#F4F4F4;margin-bottom:20px;border-radius:4px;box-shadow:rgba(0,0,0,.09) 0 2px 0}
</style>