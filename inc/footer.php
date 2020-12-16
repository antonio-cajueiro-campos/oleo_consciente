	</div>
	<footer class="footer">
        <p class="footer-p"><?php echo $system->nome_site; ?> Inc. 2020 ©Todos os direitos reservados.</p>
	    <!-- ???? <div class="footer-copyright text-center py-2">Sai Fora</div> -->
	</footer>
</div>

<div id="msgShowHtml"></div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js" charset="utf-8"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
<script src="js/jquery.mask.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<!-- <script type="text/javascript" src="js/functions.js"></script>-->

<!-- VLibras -->

<div vw class="enabled">
    <div vw-access-button class="active"></div>
    <div vw-plugin-wrapper>
      <div class="vw-plugin-top-wrapper"></div>
    </div>
</div>
<script>
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
<script src="https://vlibras.gov.br/app/vlibras-plugin.js"></script>
<script type="text/javascript">
	new window.VLibras.Widget('https://vlibras.gov.br/app');

	// inicia assim que o documento estiver pronto
    $(document).ready(function() {
		notifyUpdate();
		verifyHtmlMsg();

		// popula select com json de cidade e estados
		$.getJSON('js/estados_cidades.json', function (data) {
			var items = [];
			var options = '<option value="">Escolha um estado</option>';
			
			$.each(data, function (key, val) {
				options += '<option value="' + val.nome + '">' + val.nome + '</option>';
			});

			$("#estados").html(options);
			$("#estados").change(function () {
				var options_cidades = '';
				var str = "";
				$("#estados option:selected").each(function () {
					str += $(this).text();
				});
				$.each(data, function (key, val) {
					if(val.nome == str) {
						$.each(val.cidades, function (key_city, val_city) {
							options_cidades += '<option value="' + val_city + '">' + val_city + '</option>';
						});
					}
				});
				$("#cidades").html(options_cidades);
			}).change();
		});	
		
		$('#sidebarCollapse').on('click', function () {
			$('#sidebar').toggleClass('active');
            $(this).toggleClass('active');
        });
	});
	
	// seta a cidade/estado na página de configuração
	var set = 0;
	async function locationSet() {
		locationUpdate();
		if (set < 10) {
			set++;
			await setTimeout("locationSet()", 100);
		}		
	}

	// atualiza a cada 2 segundos as notificações
    async function notifyUpdate() {
        $.get("php/notify_count.php", function(resultado){
            $("#notifyCountLandscape").html(resultado);
			$("#notifyCountPortrait").html(resultado);
		})
		$.get("php/notify_box.php", function(resultado){
			$("#notifyBoxLandscape").html(resultado);
			$("#notifyBoxPortrait").html(resultado);
        })
        await setTimeout("notifyUpdate()", 2500);
	}
	</script>

</body>
</html>
