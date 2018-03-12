<?php
/**
* @var array $message
*/
?>
<audio autoplay="autoplay"><source src="/audio/sndGlobal.wav"><source src="/audio/sndGlobal.mp3" type="audio/mpeg"><source src="/audio/sndGlobal.ogg" type="audio/ogg; codecs=vorbis"></audio>
<div class="close-info"><span onclick="closeTipInfo()">X</span>У Вас новое сообщение :</div>
<a href="/privat_<?php echo $message['pr_id_otp']; ?>" onclick="return openPrivate(this.href,<?php echo $message['pr_id_otp']; ?>);"><strong><?php echo $message['login']; ?>:</strong> <?php echo smile(mb_substr($message['pr_text'], 0, 60)); ?> ...</a>