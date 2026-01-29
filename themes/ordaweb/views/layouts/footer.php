<footer id="footer" class="color center">

	<img alt="Ventelia" class="img-responsive center-block" src="<?= Yii::app()->baseUrl ?>/images/logo.png"
		style="max-height:70px;">
	<p>sistemaroscator.com </p>

</footer>


<script>
	window.setInterval(function () {
		/// call your function here
		Obtenernotificaciones();
		// }, 60000 * 1.5); // 1.5 minutos
	}, 60000 * .5); // 1.5 minutos


	function play() {
		var audio = document.getElementById("audio");
		audio.play();
	}

	function Obtenernotificaciones() {
		console.log('verificarnotificacion');
		$.ajax({
			url: "<?php echo $this->createUrl("administracion/revisarsonido"); ?>",
			type: "POST",
			dataType: "json",
			timeout: (120 * 1000),
			data: {},
			success: function (Response, newValue) {
				if (Response.requestresult == 'ok') {
					if (Response.activarsonido == 1) {
						play();
					}
					// $.notify(Response.message, "success");
				} else {
					// $.notify(Response.message, "error");
				}
			},
			error: function (e) {
				// $.notify("Verifica los campos e intente de nuevo", "error");
			}
		});
	}
</script>

<input type="button" value="PLAY" onclick="play()" style="display: none;">
<audio id="audio" src="<?= Yii::app()->baseUrl ?>/notificationsound_mitad.wav"></audio>