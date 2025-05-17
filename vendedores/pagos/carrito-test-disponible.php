<?php
include('conexion.php');
include('Funcion-ofertas/descuento.php');
setlocale(LC_MONETARY, 'es_MX');
session_start();
$id_usuario=$_SESSION["id_usuario"];



?>
<!DOCTYPE html>
<html lang="en" dir="ltr">


<head>
	<link rel="stylesheet" type="text/css" href="alertifyjs/css/alertify.css">
	<link rel="stylesheet" type="text/css" href="alertifyjs/css/themes/default.css">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<script src="jquery-3.2.1.min.js"></script>
	<script src="alertifyjs/alertify.js"></script>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<meta name="format-detection" content="telephone=no">
	<title>ImportadoraSupernova</title>
	<link rel="icon" type="image/png" href="images/favicon-16x16.png">
	<!-- fonts -->
	 <!-- css -->
    <link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="vendor/owl-carousel/assets/owl.carousel.min.css">
    <link rel="stylesheet" href="vendor/photoswipe/photoswipe.css">
    <link rel="stylesheet" href="vendor/photoswipe/default-skin/default-skin.css">
    <link rel="stylesheet" href="vendor/select2/css/select2.min.css">
    <link rel="stylesheet" href="css/style.css?p=1">
   
   <!-- js -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="vendor/owl-carousel/owl.carousel.min.js"></script>
    <script src="vendor/nouislider/nouislider.min.js"></script>
    <script src="vendor/photoswipe/photoswipe.min.js"></script>
    <script src="vendor/photoswipe/photoswipe-ui-default.min.js"></script>
    <script src="vendor/select2/js/select2.min.js"></script>
    <script src="js/number.js"></script>
    <script src="js/main.js?=2"></script>
    <script src="js/header.js"></script>
    <script src="vendor/svg4everybody/svg4everybody.min.js"></script>
   <script src="https://apis.google.com/js/platform.js"></script>
   <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
	<script>
	svg4everybody();</script>
	<!-- font - fontawesome -->
	<link rel="stylesheet" href="vendor/fontawesome-5.6.1/css/all.min.css">
	<!-- font - stroyka -->
	<link rel="stylesheet" href="fonts/stroyka/stroyka.css">
	<script async src="https://www.googletagmanager.com/gtag/js?id=UA-97489509-6">
	</script>

<style>HTML,BODY{cursor: url('/Logos/Normal/cursor-rv.cur'), auto;}</style>
<style type="text/css">
      .megamenu__item{

         width: 300px;
      }

      
   </style>


	<script>






	window.dataLayer = window.dataLayer || [];function gtag(){dataLayer.push(arguments);}gtag('js', new Date());gtag('config', 'UA-97489509-6');</script>
</head>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
<body  onload="Mostrar(1);" style="">
	<style type="text/css">
         .float {
             position: fixed;
             width: 50px;
             height: 35px;
             bottom: 25px;
             right: 10px;
             
             border-radius: 50px;
             text-align: center;
             font-size: 30px;
             
             z-index: 100;
         }
         .float:hover {
             /*text-decoration: none;
             color: #25d366;
             background-color: white;*/
         }

         .my-float{
            margin-top:16px;
         }
      </style>

      <?php
      $hora_whatsapp = date('G');  
      
      if ($hora_whatsapp<'12') {
         $msg='buenos dias';
      }else
      if ($hora_whatsapp>='12') {
         $msg='buenas tardes';
      }else
      if ($hora_whatsapp>='18') {
         $msg='buenas noches';
      }

      ?>
      
      <a href="https://api.whatsapp.com/send?phone=+525524954670&text=Hola <?php echo$msg?>, quisiera información referente a los productos y metodo de compra ofrecidos en su página web." class="float" target="_blank">
        
          
          <img src="Logos/icono_whatsapp_black.png" id="icono-ws" style="width:50px;transition: 1s ease;" >
      

      </a>
    
      <script>
        function cambiarImagen(nuevaImagen) {
          document.getElementById('icono-ws').src = nuevaImagen;
        }
      </script>
	<style type="text/css">
		.departments__links>li:hover>a {
			background: #8180804a;
		}
		.megamenu__links--level--1>.megamenu__item>a:hover {
			color: white;
			background-image: linear-gradient(180deg, #ec7a71 0%, #ed7c72 100%);
			padding-top: 5px;
			padding-bottom: 5px;
			padding-right: 15px;
			padding-left: 15px;
			border-bottom-left-radius: 45px;
			border-top-right-radius: 45px;
		}
		.search__input:focus~.search__button:hover {
			fill: #ff7800;
		}
		.search__button:focus,
		.search__button:hover {
			    outline: none;
    			fill: #a3a5a3;
		} 
		.search__input:hover::placeholder {
			color: #999
		}

	.btn-primary,
   .btn-primary.disabled,
   .btn-primary:disabled {
          border-color: #ec7b71;
        background: #ec7b71;
    color: #fdfeff;
    fill: #3d464d;
   }
   .btn-primary.focus,
   .btn-primary:focus,
   .btn-primary:hover {
    border-color: #101010 ;
    background: #101010 ;
    color: #fdfeff;
    fill: #3d464d;
   }
		.product-card:hover:before {
      transition-duration: 0s;
        box-shadow: inset 0px 0px 8px 0px #eb8b02;
   }
		.product-card__rating-legend {
			font-size: 13px;
			line-height: 1;
			color: #ff01af;
			margin-left: 0px;
			padding-top: 1px
		}


		.mobile-links--level--1 {
            background: #db746b;
            font-size: 14px;
            line-height: 14px;

}

	.form-control:focus {
      border-color: rgb(33 10 58 / 0%);
    outline: 0;
    box-shadow: 0 0 0 0.2rem rgb(87 87 87 / 23%);
   }
		.departments--opened .departments__links-wrapper{
			height: 481px!important;
		}


		.product__rating-legend {
			font-size: 14px;
			line-height: 20px;
			color: #ff01af;
		}
		.departments__body {
			width: 100%;
			padding-top: 42px;
			position: absolute;
			background: rgb(207 84 0);
			box-shadow: none;
			border-radius: 10px;
		}
		.departments__button-arrow{
	fill: #ffffff;
}
	</style>
	<!-- quickview-modal -->
	<div id="quickview-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-xl">
			<div class="modal-content">
			</div>
		</div>
	</div>
	<!-- quickview-modal / end -->
	<!-- mobilemenu -->
	<div class="mobilemenu">
		<div class="mobilemenu__backdrop">
		</div>
		<div class="mobilemenu__body" style="background-image: linear-gradient(135deg, #3b3b3b 0%, #101010 100%);">
			<div class="mobilemenu__header">
				<div class="mobilemenu__title">
					<a href="index.php" style="color: white;">Inicio</a></div>
					<button type="button" class="mobilemenu__close">
						<svg width="20px" height="20px">
							<use xlink:href="images/sprite.svg#cross-20">
							</use>
						</svg>
					</button>
				</div>
				<?php
				$id_usuario=$_SESSION["id_usuario"];
				$ran=rand(100000,999999);
				$ran2=rand(100000,999999);
				$var="Keyworkslinkre".$ran.$ran2;
				?>
				<!-----VERSION MOVILLLLL---->

				<div class="mobilemenu__content">
					<ul class="mobile-links mobile-links--level--0" data-collapse data-collapse-opened-class="mobile-links__item--open">
						<li class="mobile-links__item" data-collapse-item>
							<div class="mobile-links__item-title">
								<a  class="mobile-links__item-link">
								Le'Mussa</a>
								<button class="mobile-links__item-toggle" type="button" data-collapse-trigger>
									<svg class="mobile-links__item-arrow" width="12px" height="7px">
										<use xlink:href="images/sprite.svg#arrow-rounded-down-12x7">
										</use>
									</svg>
								</button>
							</div>
							<div class="mobile-links__item-sub-links" data-collapse-content>
								<ul class="mobile-links mobile-links--level--1">
									<li class="mobile-links__item" data-collapse-item>
										<div class="mobile-links__item-title">
											<a href="compras.php?relsuperNova$=<?php echo$var?>13" class="mobile-links__item-link">
											Gamas Le'Mussa</a>
										</div>
									</li>
									<li class="mobile-links__item" data-collapse-item>
										<div class="mobile-links__item-title">
											<a href="compras.php?relsuperNova$=<?php echo$var?>21" class="mobile-links__item-link">
											Productos para Le'Mussa</a>
										</div>
									</li>
									<li class="mobile-links__item" data-collapse-item>
										<div class="mobile-links__item-title">
											<a href="compras.php?relsuperNova$=<?php echo$var?>34" class="mobile-links__item-link">
											Polygel Le'Mussa</a>
										</div>
									</li>
									<li class="mobile-links__item" data-collapse-item>
										<div class="mobile-links__item-title">
											<a href="compras.php?relsuperNova$=<?php echo$var?>35" class="mobile-links__item-link">
												Gel painting Le'Mussa
											</a>
										</div>
									</li>
								</ul>
							</div>
						</li>
						<li class="mobile-links__item" data-collapse-item>
							<div class="mobile-links__item-title">
								<a  class="mobile-links__item-link">
								Productos Para Uñas</a>
								<button class="mobile-links__item-toggle" type="button" data-collapse-trigger>
									<svg class="mobile-links__item-arrow" width="12px" height="7px">
										<use xlink:href="images/sprite.svg#arrow-rounded-down-12x7">
										</use>
									</svg>
								</button>
							</div>
							<div class="mobile-links__item-sub-links" data-collapse-content>
								<ul class="mobile-links mobile-links--level--1">
									<li class="mobile-links__item" data-collapse-item>
										<div class="mobile-links__item-title">
											<a href="compras.php?relsuperNova$=<?php echo$var?>16" class="mobile-links__item-link">
											Productos para uñas</a>
										</div>
									</li>
									<li class="mobile-links__item" data-collapse-item>
										<div class="mobile-links__item-title">
											<a href="compras.php?relsuperNova$=<?php echo$var?>11" class="mobile-links__item-link">
											Decoración para uñas</a>
										</div>
									</li>
									<li class="mobile-links__item" data-collapse-item>
										<div class="mobile-links__item-title">
											<a href="compras.php?relsuperNova$=<?php echo$var?>12" class="mobile-links__item-link">
											Efectos</a>
										</div>
									</li>

									<li class="mobile-links__item" data-collapse-item>
										<div class="mobile-links__item-title">
											<a href="compras.php?relsuperNova$=<?php echo$var?>14" class="mobile-links__item-link">
											Lámparas para uñas</a>
										</div>
									</li>
									<li class="mobile-links__item" data-collapse-item>
										<div class="mobile-links__item-title">
											<a href="compras.php?relsuperNova$=<?php echo$var?>15" class="mobile-links__item-link">
											Limas</a>
										</div>
									</li>
									<li class="mobile-links__item" data-collapse-item>
										<div class="mobile-links__item-title">
											<a href="compras.php?relsuperNova$=<?php echo$var?>17" class="mobile-links__item-link">
											Pulidores</a>
										</div>
									</li>
									<li class="mobile-links__item" data-collapse-item>
										<div class="mobile-links__item-title">
											<a href="compras.php?relsuperNova$=<?php echo$var?>18" class="mobile-links__item-link">
											Puntas para pulidores</a>
										</div>
									</li>
									<li class="mobile-links__item" data-collapse-item>
										<div class="mobile-links__item-title">
											<a href="compras.php?relsuperNova$=<?php echo$var?>19" class="mobile-links__item-link">
											Tipos de uñas</a>
										</div>
									</li>
									<li class="mobile-links__item" data-collapse-item>
										<div class="mobile-links__item-title">
											<a href="compras.php?relsuperNova$=<?php echo$var?>22" class="mobile-links__item-link">
											Extractores de polvo</a>
										</div>
									</li>
									<li class="mobile-links__item" data-collapse-item>
										<div class="mobile-links__item-title">
											<a href="compras.php?relsuperNova$=<?php echo$var?>23" class="mobile-links__item-link">
											Utensilios</a>
										</div>
									</li>

















								</ul>
							</div>
						</li>
						<li class="mobile-links__item" data-collapse-item>
							<div class="mobile-links__item-title">
								<a class="mobile-links__item-link">
									Productos Para cejas
								</a>
								<button class="mobile-links__item-toggle" type="button" data-collapse-trigger>
									<svg class="mobile-links__item-arrow" width="12px" height="7px">
										<use xlink:href="images/sprite.svg#arrow-rounded-down-12x7">
										</use>
									</svg>
								</button>
							</div>
							<div class="mobile-links__item-sub-links" data-collapse-content>
								<ul class="mobile-links mobile-links--level--1">
									<li class="mobile-links__item" data-collapse-item>
										<div class="mobile-links__item-title">
											<a href="compras.php?relsuperNova$=<?php echo$var?>39" class="mobile-links__item-link">
											Productos Para cejas</a>
										</div>
									</li>
								</ul>
								<ul class="mobile-links mobile-links--level--1">
									<li class="mobile-links__item" data-collapse-item>
										<div class="mobile-links__item-title">
											<a href="compras.php?relsuperNova$=<?php echo$var?>38" class="mobile-links__item-link">
											Microblading</a>
										</div>
									</li>
								</ul>
							</div>
						</li>
						<li class="mobile-links__item" data-collapse-item>
							<div class="mobile-links__item-title">
								<a  class="mobile-links__item-link">
								Productos Para Pestañas</a>
								<button class="mobile-links__item-toggle" type="button" data-collapse-trigger>
									<svg class="mobile-links__item-arrow" width="12px" height="7px">
										<use xlink:href="images/sprite.svg#arrow-rounded-down-12x7">
										</use>
									</svg>
								</button>
							</div>
							<div class="mobile-links__item-sub-links" data-collapse-content>
								<ul class="mobile-links mobile-links--level--1">
									<li class="mobile-links__item" data-collapse-item>
										<div class="mobile-links__item-title">
											<a href="compras.php?relsuperNova$=<?php echo$var?>9" class="mobile-links__item-link">
											Pestañas</a>
										</div>
									</li>
									<li class="mobile-links__item" data-collapse-item>
										<div class="mobile-links__item-title">
											<a href="compras.php?relsuperNova$=<?php echo$var?>24" class="mobile-links__item-link">
											Pegamentos</a>
										</div>
									</li>
									<li class="mobile-links__item" data-collapse-item>
										<div class="mobile-links__item-title">
											<a href="compras.php?relsuperNova$=<?php echo$var?>25" class="mobile-links__item-link">
											Removedor</a>
										</div>
									</li>
									<li class="mobile-links__item" data-collapse-item>
										<div class="mobile-links__item-title">
											<a href="compras.php?relsuperNova$=<?php echo$var?>26" class="mobile-links__item-link">
											Utensilios y herramientas</a>
										</div>
									</li>
									<li class="mobile-links__item" data-collapse-item>
										<div class="mobile-links__item-title">
											<a href="compras.php?relsuperNova$=<?php echo$var?>27" class="mobile-links__item-link">
											Rizados permanentes</a>
										</div>
									</li>
								</ul>
							</div>
						</li>
						<li class="mobile-links__item" data-collapse-item>
							<div class="mobile-links__item-title">
								<a  class="mobile-links__item-link">
								Productos para el cabello</a>
								<button class="mobile-links__item-toggle" type="button" data-collapse-trigger>
									<svg class="mobile-links__item-arrow" width="12px" height="7px">
										<use xlink:href="images/sprite.svg#arrow-rounded-down-12x7">
										</use>
									</svg>
								</button>
							</div>
							<div class="mobile-links__item-sub-links" data-collapse-content>
								<ul class="mobile-links mobile-links--level--1">
									<li class="mobile-links__item" data-collapse-item>
										<div class="mobile-links__item-title">
											<a href="compras.php?relsuperNova$=<?php echo$var?>2" class="mobile-links__item-link">
											Cepillo Alaciador</a>
										</div>
									</li>
									<li class="mobile-links__item" data-collapse-item>
										<div class="mobile-links__item-title">
											<a href="compras.php?relsuperNova$=<?php echo$var?>4" class="mobile-links__item-link">
											Planchas</a>
										</div>
									</li>
									<li class="mobile-links__item" data-collapse-item>
										<div class="mobile-links__item-title">
											<a href="compras.php?relsuperNova$=<?php echo$var?>5" class="mobile-links__item-link">
											Rizadoras</a>
										</div>
									</li>
									<li class="mobile-links__item" data-collapse-item>
										<div class="mobile-links__item-title">
											<a href="compras.php?relsuperNova$=<?php echo$var?>3" class="mobile-links__item-link">
											Ferros</a>
										</div>
									</li>
									<li class="mobile-links__item" data-collapse-item>
										<div class="mobile-links__item-title">
											<a href="compras.php?relsuperNova$=<?php echo$var?>6" class="mobile-links__item-link">
											Secadores</a>
										</div>
									</li>
								</ul>
							</div>
						</li>
						<li class="mobile-links__item" data-collapse-item>
							<div class="mobile-links__item-title">
								<a  class="mobile-links__item-link">
								Brochas y relacionados</a>
								<button class="mobile-links__item-toggle" type="button" data-collapse-trigger>
									<svg class="mobile-links__item-arrow" width="12px" height="7px">
										<use xlink:href="images/sprite.svg#arrow-rounded-down-12x7">
										</use>
									</svg>
								</button>
							</div>
							<div class="mobile-links__item-sub-links" data-collapse-content>
								<ul class="mobile-links mobile-links--level--1">
									<li class="mobile-links__item" data-collapse-item>
										<div class="mobile-links__item-title">
											<a href="compras.php?relsuperNova$=<?php echo$var?>1" class="mobile-links__item-link">
											Brochas</a>
										</div>
									</li>
									<li class="mobile-links__item" data-collapse-item>
										<div class="mobile-links__item-title">
											<a href="compras.php?relsuperNova$=<?php echo$var?>28" class="mobile-links__item-link">
											Utensilios</a>
										</div>
									</li>

								</ul>
							</div>
						</li>
						<li class="mobile-links__item" data-collapse-item>
							<div class="mobile-links__item-title">
								<a  class="mobile-links__item-link">
								Cosmetiqueras</a>
								<button class="mobile-links__item-toggle" type="button" data-collapse-trigger>
									<svg class="mobile-links__item-arrow" width="12px" height="7px">
										<use xlink:href="images/sprite.svg#arrow-rounded-down-12x7">
										</use>
									</svg>
								</button>
							</div>
							<div class="mobile-links__item-sub-links" data-collapse-content>
								<ul class="mobile-links mobile-links--level--1">
									<li class="mobile-links__item" data-collapse-item>
										<div class="mobile-links__item-title">
											<a href="compras.php?relsuperNova$=<?php echo$var?>7" class="mobile-links__item-link">
											Cosmetiqueras</a>
										</div>
									</li>

								</ul>
							</div>
						</li>
						<li class="mobile-links__item" data-collapse-item>
							<div class="mobile-links__item-title">
								<a  class="mobile-links__item-link">
								Masajeadores Corporales</a>
								<button class="mobile-links__item-toggle" type="button" data-collapse-trigger>
									<svg class="mobile-links__item-arrow" width="12px" height="7px">
										<use xlink:href="images/sprite.svg#arrow-rounded-down-12x7">
										</use>
									</svg>
								</button>
							</div>
							<div class="mobile-links__item-sub-links" data-collapse-content>
								<ul class="mobile-links mobile-links--level--1">
									<li class="mobile-links__item" data-collapse-item>
										<div class="mobile-links__item-title">
											<a href="compras.php?relsuperNova$=<?php echo$var?>8" class="mobile-links__item-link">
											Masajeadores corporales</a>
										</div>
									</li>

								</ul>
							</div>
						</li>
						<li class="mobile-links__item" data-collapse-item>
							<div class="mobile-links__item-title">
								<a  class="mobile-links__item-link">
								Rasuradoras</a>
								<button class="mobile-links__item-toggle" type="button" data-collapse-trigger>
									<svg class="mobile-links__item-arrow" width="12px" height="7px">
										<use xlink:href="images/sprite.svg#arrow-rounded-down-12x7">
										</use>
									</svg>
								</button>
							</div>
							<div class="mobile-links__item-sub-links" data-collapse-content>
								<ul class="mobile-links mobile-links--level--1">
									<li class="mobile-links__item" data-collapse-item>
										<div class="mobile-links__item-title">
											<a href="compras.php?relsuperNova$=<?php echo$var?>10" class="mobile-links__item-link">
											Rasuradoras</a>
										</div>
									</li>
									<li class="mobile-links__item" data-collapse-item>
										<div class="mobile-links__item-title">
											<a href="compras.php?relsuperNova$=<?php echo$var?>29" class="mobile-links__item-link">
											Depiladores</a>
										</div>
									</li>

								</ul>
							</div>
						</li>
						<li class="mobile-links__item" data-collapse-item>
							<div class="mobile-links__item-title">
								<a  class="mobile-links__item-link">
								Le'Mussa Home</a>
								<button class="mobile-links__item-toggle" type="button" data-collapse-trigger>
									<svg class="mobile-links__item-arrow" width="12px" height="7px">
										<use xlink:href="images/sprite.svg#arrow-rounded-down-12x7">
										</use>
									</svg>
								</button>
							</div>
							<div class="mobile-links__item-sub-links" data-collapse-content>
								<ul class="mobile-links mobile-links--level--1">
									<li class="mobile-links__item" data-collapse-item>
										<div class="mobile-links__item-title">
											<a href="compras.php?relsuperNova$=<?php echo$var?>36" class="mobile-links__item-link">
											Productos para el Hogar</a>
										</div>
									</li>

								</ul>
							</div>
						</li>
						<li class="mobile-links__item" data-collapse-item>
							<div class="mobile-links__item-title">
								<a  class="mobile-links__item-link">
								Otros</a>
								<button class="mobile-links__item-toggle" type="button" data-collapse-trigger>
									<svg class="mobile-links__item-arrow" width="12px" height="7px">
										<use xlink:href="images/sprite.svg#arrow-rounded-down-12x7">
										</use>
									</svg>
								</button>
							</div>
							<div class="mobile-links__item-sub-links" data-collapse-content>
								<ul class="mobile-links mobile-links--level--1">
									<li class="mobile-links__item" data-collapse-item>
										<div class="mobile-links__item-title">
											<a href="compras.php?relsuperNova$=<?php echo$var?>20" class="mobile-links__item-link">
											Organizadores</a>
										</div>
									</li>
									<li class="mobile-links__item" data-collapse-item>
										<div class="mobile-links__item-title">
											<a href="compras.php?relsuperNova$=<?php echo$var?>30" class="mobile-links__item-link">
											Electricos</a>
										</div>
									</li>
									<li class="mobile-links__item" data-collapse-item>
										<div class="mobile-links__item-title">
											<a href="compras.php?relsuperNova$=<?php echo$var?>31" class="mobile-links__item-link">
											Lámparas de escritorios</a>
										</div>
									</li>
									<li class="mobile-links__item" data-collapse-item>
										<div class="mobile-links__item-title">
											<a href="compras.php?relsuperNova$=<?php echo$var?>32" class="mobile-links__item-link">
											Espejos</a>
										</div>
									</li>
									<li class="mobile-links__item" data-collapse-item>
										<div class="mobile-links__item-title">
											<a href="compras.php?relsuperNova$=<?php echo$var?>33" class="mobile-links__item-link">
											Productos corporales</a>
										</div>
									</li>
									<li class="mobile-links__item" data-collapse-item>
										<div class="mobile-links__item-title">
											<a href="compras.php?relsuperNova$=<?php echo$var?>37" class="mobile-links__item-link">
											Fotografía</a>
										</div>
									</li>
								</ul>
							</div>
						</li>
						<div style="text-align: center;margin-top: 10px;display: flex;align-items: center;justify-content: center;height: 45vh;">
               <img src="Logos/Normal/logo_1.png" style="width: 80%;">
            </div>
					</ul>
				</div>





			</div>


		</div>

		<!-- mobilemenu / end -->
		<!-- site -->
		<div class="site" >
			<!-- mobile site__header -->
			<header class="site__header d-lg-none">
				<div class="mobile-header mobile-header--sticky mobile-header--stuck">
					<div class="mobile-header__panel" style="	background-image: linear-gradient(135deg, #3b3b3b 0%, #101010 100%);">
						<div class="container" style="    padding-left: 7px;">
							<div class="mobile-header__body">
								<button class="mobile-header__menu-button" style="width: 55px;">
									<div style="color: white;font-size: 19px;">Menú</div>

								</button>
							<!--<a class="mobile-header__logo" href="index.php">
								<img src="images/3.png" width="55px" height="40px">
							</a>-->

							<div class="site-header__search">
								<div class="search">
									<form class="search__form" action="compras.php" style="margin-left: 8px;">
      <input  class="search__input" name="search" placeholder="Buscar Productos" aria-label="Site search" type="text" autocomplete="off" style="background: #ed7c72;color: white;border-bottom-left-radius: 10px;border-top-left-radius: 10px;border-bottom-right-radius: 0px;border-top-right-radius: 0px;">
      <button class="search__button" type="submit" style="border-bottom-right-radius: 10px;border-top-right-radius: 10px;    background: #ed7c72;fill: #ffffff;">
         <svg width="20px" height="20px">
            <use xlink:href="images/sprite.svg#search-20"></use>
         </svg>
      </button>
      <div class="search__border">
      </div>
   </form>
								</div>
							</div>
							<div class="mobile-header__indicators">
								
								<div class="indicator indicator--mobile">
									<?php
									$comp='0';
									$id_usuario=$_SESSION["id_usuario"];
									
									
									$sql = "SELECT * FROM carrito where id_usuario='$id_usuario' ";
									$result=$mysqli->query($sql);
									while ($columna = mysqli_fetch_array( $result ))
									{
										$comp=$comp+1;
									}
									?>
									<a href="carrito.php" class="indicator__button">
										<span class="indicator__area">
											<i class='fas fa-cart-arrow-down' style="font-size:20px;"></i>
											<span class="indicator__value">
												<?php echo$comp?></span>
											</span>
										</a>
										



										<div class="indicator__dropdown" style="max-height: 398px; overflow-y: auto;  box-shadow: 0 1px 15px rgba(0,0,0,.25);">
											<!-- .dropcart -->
											<div class="dropcart dropcart--style--dropdown">
												<div class="dropcart__body">
													<div class="dropcart__products-list">
														


														<?php
														
														$id_usuario=$_SESSION["id_usuario"];
														$total='0';
														$sql = "SELECT * FROM carrito where id_usuario='$id_usuario' ";
														$result=$mysqli->query($sql);
														while ($columna = mysqli_fetch_array( $result ))
														{
															$id_pro = $columna['id_producto'];
															$cantidad_pro = $columna['cantidad'];
															$precio_establecido = $columna['precio'];
															$color = $columna['color'];

															$sqlx = "SELECT * FROM productos where id='$id_pro' ";
															$resultx=$mysqli->query($sqlx);
															while ($columnax = mysqli_fetch_array( $resultx ))
															{
																$nombre_pro = strtolower($columnax['nombre']);
																$descripcion_pro =strtolower($columnax['descripcion']);
																$precio_u = $columnax['preciou'];
															}
															$sqll = "SELECT * FROM img where id_producto='$id_pro' ORDER BY id DESC ";
															$resultl=$mysqli->query($sqll);
															while ($columnal = mysqli_fetch_array( $resultl ))
															{
																$ruta = $columnal['ruta'];
																$a = $columnal['a'];

																$url=$ruta.$a;

																$ur = substr($url, 6);

															}

															$sub=$cantidad_pro*$precio_establecido; 
															$total=$total+$sub;

															$ran=rand(100000,999999);
															$ran2=rand(100000,999999);
															$var="Keyworkslinkre".$ran.$ran2.$id_pro;
															
															?>

															<div class="dropcart__product">
																<div class="product-image dropcart__product-image">
																	<a href="product.php?relsuperNova$=<?php echo$var?>" class="product-image__body">
																		<img class="product-image__img" src="<?php echo$ur?>" alt="">
																	</a>
																</div>
																<div class="dropcart__product-info">
																	<div class="dropcart__product-name">
																		<a href="product.php?relsuperNova$=<?php echo$var?>">
																			<?php echo ucfirst($nombre_pro)?></a>
																		</div>
																		<ul class="dropcart__product-options">
																			<li>
																				<?php echo ucfirst($descripcion_pro)?></li>
																				
																			</ul>
																			<div class="dropcart__product-meta">
																				<span class="dropcart__product-quantity">
																					<?php echo$cantidad_pro?></span>
																					× <span class="dropcart__product-price">
																						$<?php echo$precio_establecido?></span>
																					</div>
																				</div>
																				
																			</div>

																			<?php

																			
																		}
																		?>






																	</div>
																	



																	<div class="dropcart__totals">
																		<table>
																			<tbody>
																				<tr>
																					<th>
																					Subtotal</th>
																					<td>
																						$<?php echo$total.".00"?></td>
																					</tr>
																					
																				</tbody>
																			</table>
																		</div>
																		<div class="dropcart__buttons">
												<!--<a class="btn btn-secondary" href="carrito.php">
												Ir al Carrito</a>-->
												<a class="btn btn-primary" href="carrito.php">
												Ir al Carrito</a>
											</div>
										</div>
									</div>
									<!-- .dropcart / end -->
								</div>
							</div>
							<div class="indicator indicator--mobile">
								<a href="iniciar.php" class="indicator__button">
									<i class='fas fa-user-alt' style="font-size: 20px;"></i>
								</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</header>
	<!-- mobile site__header / end -->
	<!-- desktop site__header -->
	<header class="site__header d-lg-block d-none">
		
		<div class="site-header">
			<!-- .topbar -->
			
			<!-- .topbar / end -->
			<div class="site-header__middle container">
				<div class="site-header__logo">
					<a href="index.php" style="text-align: center;">
						<img src="Logos/Normal/logo_1.png" style="margin-top: 25px;width: 50%;">
					</a>
				</div>
				<div class="site-header__search">
					<div class="search">
						<form class="search__form" action="compras.php">
							<input class="search__input" name="search" placeholder="Buscar Productos" aria-label="Site search" type="text" autocomplete="off" style="border-bottom-left-radius: 10px;color: white;border-top-left-radius: 10px;background: #ec7b71;">
							<button class="search__button" type="submit" style="border-bottom-right-radius: 10px; border-top-right-radius: 10px;background: #ec7b71;">
								<svg width="20px" height="20px">
									<use xlink:href="images/sprite.svg#search-20">
									</use>
								</svg>
							</button>
							<div class="search__border">
							</div>
						</form>
					</div>
				</div>
<div class="site-header__phone">
         <div class="site-header__phone-title" style="font-size: 17px;color: #000;margin-top: 36px;">
         </div>
         <p style="margin-bottom: 2px; font-size: 15px;margin-bottom: 3px;"><b style="color: #ec7d73"><i class='fas fa-user-tie'  style="    color: #ec7d73"></i> Atención al cliente</b></p>
         <div class="site-header__phone-number" style="font-size: 15px;margin-bottom: 3px;">
         <a href="https://wa.me/+525524954670" style="    color: #4d4d4d">5524954670</a>
         </div>
         <p style="margin-bottom: 2px; font-size: 15px;margin-bottom: 3px;"><b style="color: #ec7d73"><i class='far fa-handshake'  style="    color: #ec7d73"></i> Entregas en CDMX</b></p>
         <div class="site-header__phone-number" style="font-size: 15px;margin-bottom: 3px;">
         <a href="https://wa.me/+5215579978519" style="    color: #4d4d4d">+52 1 55 7997-8519</a>
         </div>
         <p style="margin-bottom: 2px; font-size: 15px;margin-bottom: 3px;"><b style="color: #ec7d73"><i class='fas fa-shipping-fast' style="    color: #ec7d73"></i> Envíos a nivel nacional</b></p>
         <div class="site-header__phone-number" style="font-size: 15px;margin-bottom: 3px;">
         <a href="https://wa.me/+5215545564443" style="    color: #4d4d4d">+52 1 55 4556-4443</a>
         </div>
      </div>
			</div>
			<br>
			<div class="site-header__nav-panel">
				<div class="nav-panel" style="background-image: linear-gradient(135deg, #3b3b3b 0%, #101010 100%);">
					<div class="nav-panel__container container">
						<div class="nav-panel__row">
							<div class="nav-panel__departments">
								<!-- .departments -->
								<div class="departments" data-departments-fixed-by="">
									<div class="departments__body" style="background-image: linear-gradient(135deg, #f59991 0%, #eb776d 100%);border-top-left-radius: 35px;border-bottom-right-radius: 35px;" >
										<div class="departments__links-wrapper">
											<ul class="departments__links">
												<?php


												$var="Keyworkslinkre".$ran.$ran2;		

												?>										
												<li class="departments__item">
													<a href="#">
														Le'Mussa
														<svg class="departments__link-arrow" width="6px" height="9px">
															<use xlink:href="images/sprite.svg#arrow-rounded-right-6x9">
															</use>
														</svg>
													</a>
													<div class="departments__megamenu departments__megamenu--xl">
														<div class="megamenu megamenu--departments" style="width: 34%;padding-right: 0px;background-image: url('images/megamenu/megamenu-11.jpg');">
															<div class="row">
																<div class="col-3" >
																	<ul class="megamenu__links megamenu__links--level--0" style="    width: 468%;">
																		<li class="megamenu__item megamenu__item--with-submenu">
																			<ul class="megamenu__links megamenu__links--level--1">
																				<li class="megamenu__item">
																					<a  href="compras.php?relsuperNova$=<?php echo$var?>13">
																					- Gamas Le'Mussa</a>
																				</li>
																				<li class="megamenu__item">
																					<a  href="compras.php?relsuperNova$=<?php echo$var?>21">
																					- Productos para Le'Mussa</a>
																				</li>
																				<li class="megamenu__item">
																					<a  href="compras.php?relsuperNova$=<?php echo$var?>34">
																					- Polygel Le'Mussa</a>
																				</li>
																				<li class="megamenu__item">
																					<a  href="compras.php?relsuperNova$=<?php echo$var?>35">
																						- Gel painting Le'Mussa
																					</a>
																				</li>
																			</ul>
																		</li>
																	</ul>
																</div>
															</div>
														</div>

													</div>
												</li>
												<li class="departments__item" >
													<a href="#">
														Productos Para Uñas 
														<svg class="departments__link-arrow" width="6px" height="9px">
															<use xlink:href="images/sprite.svg#arrow-rounded-right-6x9">
															</use>
														</svg>
													</a>
													<div class="departments__megamenu departments__megamenu--lg">
														<!-- .megamenu -->
														<div class="megamenu megamenu--departments" style="background-image: url('images/megamenu/megamenu-1.jpg'); width: 60%;">
															<div class="row">
																<div class="col-4">
																	<ul class="megamenu__links megamenu__links--level--0">
																		<li class="megamenu__item megamenu__item--with-submenu">
																			<ul class="megamenu__links megamenu__links--level--1">
																				<li class="megamenu__item">
																					<a href="compras.php?relsuperNova$=<?php echo$var?>16">
																					- Productos para uñas</a>
																				</li>
																				<li class="megamenu__item">
																					<a href="compras.php?relsuperNova$=<?php echo$var?>11">
																					- Decoración para uñas</a>
																				</li>
																				<li class="megamenu__item">
																					<a href="compras.php?relsuperNova$=<?php echo$var?>12">
																					- Efectos</a>
																				</li>
																				<li class="megamenu__item">
																					<a href="compras.php?relsuperNova$=<?php echo$var?>14">
																					- Lámparas para uñas</a>
																				</li>
																				<li class="megamenu__item">
																					<a href="compras.php?relsuperNova$=<?php echo$var?>15">
																					- Limas</a>
																				</li>
																				<li class="megamenu__item">
																					<a href="compras.php?relsuperNova$=<?php echo$var?>17">
																					- Pulidores</a>
																				</li>
																				<li class="megamenu__item">
																					<a href="compras.php?relsuperNova$=<?php echo$var?>18">
																					- Puntas para pulidores</a>
																				</li>
																				<li class="megamenu__item">
																					<a href="compras.php?relsuperNova$=<?php echo$var?>19">
																					- Tipos de uñas</a>
																				</li>
																				<li class="megamenu__item">
																					<a href="compras.php?relsuperNova$=<?php echo$var?>22">
																					- Extractores de polvo</a>
																				</li>
																				<li class="megamenu__item">
																					<a href="compras.php?relsuperNova$=<?php echo$var?>23">
																					- Utensilios</a>
																				</li>
																			</ul>
																		</li>
																	</ul>
																</div>
															</div>
														</div>
														<!-- .megamenu / end -->
													</div>
												</li>
												<li class="departments__item">
													<a href="#">
														Productos Para cejas
														<svg class="departments__link-arrow" width="6px" height="9px">
															<use xlink:href="images/sprite.svg#arrow-rounded-right-6x9">
															</use>
														</svg>
													</a>
													<div class="departments__megamenu departments__megamenu--lg">
														<!-- .megamenu -->
														<div class="megamenu megamenu--departments" style="background-image: url('images/megamenu/megamenu-2.jpg'); width: 50%;">
															<div class="row">
																<div class="col-4">
																	<ul class="megamenu__links megamenu__links--level--0">
																		<li class="megamenu__item megamenu__item--with-submenu">
																			<ul class="megamenu__links megamenu__links--level--1">

																				<li class="megamenu__item">
																					<a href="compras.php?relsuperNova$=<?php echo$var?>39">
																					- Productos para cejas</a>
																				</li> 
																				<li class="megamenu__item">
																					<a href="compras.php?relsuperNova$=<?php echo$var?>38">
																					- Microblading</a>
																				</li> 
																			</ul>
																		</li>
																	</ul>
																</div>
															</div>
														</div>
														<!-- .megamenu / end -->
													</div>
												</li>
												<li class="departments__item">
													<a href="#">
														Productos Para Pestañas 
														<svg class="departments__link-arrow" width="6px" height="9px">
															<use xlink:href="images/sprite.svg#arrow-rounded-right-6x9">
															</use>
														</svg>
													</a>
													<div class="departments__megamenu departments__megamenu--lg">
														<!-- .megamenu -->
														<div class="megamenu megamenu--departments" style="background-image: url('images/megamenu/megamenu-2.jpg'); width: 50%;">
															<div class="row">
																<div class="col-4">
																	<ul class="megamenu__links megamenu__links--level--0">
																		<li class="megamenu__item megamenu__item--with-submenu">
																			<ul class="megamenu__links megamenu__links--level--1">
																				<li class="megamenu__item">
																					<a href="compras.php?relsuperNova$=<?php echo$var?>9">
																					- Pestañas</a>
																				</li>
																				<li class="megamenu__item">
																					<a href="compras.php?relsuperNova$=<?php echo$var?>24">
																					- Pegamentos</a>
																				</li>
																				<li class="megamenu__item">
																					<a href="compras.php?relsuperNova$=<?php echo$var?>25">
																					- Removedor</a>
																				</li>
																				<li class="megamenu__item">
																					<a href="compras.php?relsuperNova$=<?php echo$var?>26">
																					- Utensilios y herramientas</a>
																				</li>
																				<li class="megamenu__item">
																					<a href="compras.php?relsuperNova$=<?php echo$var?>27">
																					- Rizados permanentes</a>
																				</li>

																			</ul>
																		</li>
																	</ul>
																</div>
															</div>
														</div>
														<!-- .megamenu / end -->
													</div>
												</li>
												<li class="departments__item">
													<a href="#">
														Productos para el cabello 
														<svg class="departments__link-arrow" width="6px" height="9px">
															<use xlink:href="images/sprite.svg#arrow-rounded-right-6x9">
															</use>
														</svg>
													</a>
													<div class="departments__megamenu departments__megamenu--nl">
														<!-- .megamenu -->
														<div class="megamenu megamenu--departments" style="background-image: url('images/megamenu/megamenu-3.jpg'); width: 70%;">
															<div class="row">
																<div class="col-4">
																	<ul class="megamenu__links megamenu__links--level--0">
																		<li class="megamenu__item megamenu__item--with-submenu">
																			<ul class="megamenu__links megamenu__links--level--1">
																				<li class="megamenu__item">
																					<a href="compras.php?relsuperNova$=<?php echo$var?>2">
																					- Cepillo Alaciador</a>
																				</li>
																				<li class="megamenu__item">
																					<a href="compras.php?relsuperNova$=<?php echo$var?>4">
																					- Planchas</a>
																				</li>
																				<li class="megamenu__item">
																					<a href="compras.php?relsuperNova$=<?php echo$var?>5">
																					- Rizadoras</a>
																				</li>
																				<li class="megamenu__item">
																					<a href="compras.php?relsuperNova$=<?php echo$var?>3">
																					- Ferros</a>
																				</li>
																				<li class="megamenu__item">
																					<a href="compras.php?relsuperNova$=<?php echo$var?>6">
																					- Secadores</a>
																				</li>
																			</ul>
																		</li>
																	</ul>
																</div>
															</div>
														</div>
														<!-- .megamenu / end -->
													</div>
												</li>
												<li class="departments__item">
													<a href="#">
														Brochas y relacionados
														<svg class="departments__link-arrow" width="6px" height="9px">
															<use xlink:href="images/sprite.svg#arrow-rounded-right-6x9">
															</use>
														</svg>
													</a>
													<div class="departments__megamenu departments__megamenu--nl">
														<!-- .megamenu -->
														<div class="megamenu megamenu--departments" style="background-image: url('images/megamenu/megamenu-4.jpg'); width: 70%;">
															<div class="row">
																<div class="col-4">
																	<ul class="megamenu__links megamenu__links--level--0">
																		<li class="megamenu__item megamenu__item--with-submenu">
																			<ul class="megamenu__links megamenu__links--level--1">
																				<li class="megamenu__item">
																					<a href="compras.php?relsuperNova$=<?php echo$var?>1">
																					- Brochas</a>
																				</li>
																				<li class="megamenu__item">
																					<a href="compras.php?relsuperNova$=<?php echo$var?>28">
																					- Utensilios</a>
																				</li>
																			</ul>
																		</li>
																	</ul>
																</div>
															</div>
														</div>
														<!-- .megamenu / end -->
													</div>
												</li>
												<li class="departments__item">
													<a href="#">
														Cosmetiqueras 
														<svg class="departments__link-arrow" width="6px" height="9px">
															<use xlink:href="images/sprite.svg#arrow-rounded-right-6x9">
															</use>
														</svg>
													</a>
													<div class="departments__megamenu departments__megamenu--nl">
														<!-- .megamenu -->
														<div class="megamenu megamenu--departments" style="background-image: url('images/megamenu/megamenu-5.jpg'); width: 75%;">
															<div class="row">
																<div class="col-4">
																	<ul class="megamenu__links megamenu__links--level--0">
																		<li class="megamenu__item megamenu__item--with-submenu">
																			<ul class="megamenu__links megamenu__links--level--1">
																				<li class="megamenu__item">
																					<a href="compras.php?relsuperNova$=<?php echo$var?>7">
																					- Cosmetiqueras</a>
																				</li>
																			</ul>
																		</li>
																	</ul>
																</div>
															</div>
														</div>
														<!-- .megamenu / end -->
													</div>
												</li>
												<li class="departments__item">
													<a href="#">
														Masajeadores Corporales 
														<svg class="departments__link-arrow" width="6px" height="9px">
															<use xlink:href="images/sprite.svg#arrow-rounded-right-6x9">
															</use>
														</svg>
													</a>
													<div class="departments__megamenu departments__megamenu--nl">
														<!-- .megamenu -->
														<div class="megamenu megamenu--departments" style=" width: 60%;background-image: url('images/megamenu/megamenu-17.jpg');">
															<div class="row">
																<div class="col-4">
																	<ul class="megamenu__links megamenu__links--level--0">
																		<li class="megamenu__item megamenu__item--with-submenu">
																			<ul class="megamenu__links megamenu__links--level--1">
																				<li class="megamenu__item">
																					<a href="compras.php?relsuperNova$=<?php echo$var?>8">
																					- Masajeadores corporales</a>
																				</li>
																			</ul>
																		</li>
																	</ul>
																</div>
															</div>
														</div>
														<!-- .megamenu / end -->
													</div>
												</li>
												<li class="departments__item">
													<a href="#">
														Rasuradoras
														<svg class="departments__link-arrow" width="6px" height="9px">
															<use xlink:href="images/sprite.svg#arrow-rounded-right-6x9">
															</use>
														</svg>
													</a>
													<div class="departments__megamenu departments__megamenu--nl">
														<!-- .megamenu -->
														<div class="megamenu megamenu--departments" style=" width: 60%;background-image: url('images/megamenu/megamenu-15.jpg');">
															<div class="row">
																<div class="col-4">
																	<ul class="megamenu__links megamenu__links--level--0">
																		<li class="megamenu__item megamenu__item--with-submenu">
																			<ul class="megamenu__links megamenu__links--level--1">
																				<li class="megamenu__item">
																					<a href="compras.php?relsuperNova$=<?php echo$var?>10">
																					- Rasuradoras</a>
																				</li>
																				<li class="megamenu__item">
																					<a href="compras.php?relsuperNova$=<?php echo$var?>29">
																					- Depiladores</a>
																				</li>
																			</ul>
																		</li>
																	</ul>
																</div>
															</div>
														</div>
														<!-- .megamenu / end -->
													</div>
												</li>
												<li class="departments__item">
													<a href="#">
														Le'Mussa Home
														<svg class="departments__link-arrow" width="6px" height="9px">
															<use xlink:href="images/sprite.svg#arrow-rounded-right-6x9">
															</use>
														</svg>
													</a>
													<div class="departments__megamenu departments__megamenu--nl">

														<div class="megamenu megamenu--departments" style="background-image: url('images/megamenu/hogar2.png'); width: 60%;">
															<div class="row">
																<div class="col-4">
																	<ul class="megamenu__links megamenu__links--level--0">
																		<li class="megamenu__item megamenu__item--with-submenu">
																			<ul class="megamenu__links megamenu__links--level--1">
																				<li class="megamenu__item">
																					<a href="compras.php?relsuperNova$=<?php echo$var?>36">
																					- Productos para el hogar</a>
																				</li>

																			</ul>
																		</li>
																	</ul>
																</div>
															</div>
														</div>

													</div>
												</li>
												<li class="departments__item">
													<a href="#">
														Otros
														<svg class="departments__link-arrow" width="6px" height="9px">
															<use xlink:href="images/sprite.svg#arrow-rounded-right-6x9">
															</use>
														</svg>
													</a>
													<div class="departments__megamenu departments__megamenu--nl">
														<!-- .megamenu -->
														<div class="megamenu megamenu--departments" style="background-image: url('images/megamenu/megamenu-8.jpg'); width: 60%;">
															<div class="row">
																<div class="col-4">
																	<ul class="megamenu__links megamenu__links--level--0">
																		<li class="megamenu__item megamenu__item--with-submenu">
																			<ul class="megamenu__links megamenu__links--level--1">
																				<li class="megamenu__item">
																					<a href="compras.php?relsuperNova$=<?php echo$var?>20">
																					- Organizadores</a>
																				</li>
																				<li class="megamenu__item">
																					<a href="compras.php?relsuperNova$=<?php echo$var?>30">
																					- Electricos</a>
																				</li>
																				<li class="megamenu__item">
																					<a href="compras.php?relsuperNova$=<?php echo$var?>31">
																					- Lámparas de escritorios</a>
																				</li>
																				<li class="megamenu__item">
																					<a href="compras.php?relsuperNova$=<?php echo$var?>32">
																					- Espejos</a>
																				</li>
																				<li class="megamenu__item">
																					<a href="compras.php?relsuperNova$=<?php echo$var?>33">
																					- Productos corporales</a>
																				</li>
																				<li class="megamenu__item">
																					<a href="compras.php?relsuperNova$=<?php echo$var?>37">
																					- Fotografia</a>
																				</li>
																			</ul>
																		</li>
																	</ul>
																</div>
															</div>
														</div>
														<!-- .megamenu / end -->
													</div>
												</li>

											</ul>
										</div>
									</div>
									<button class="departments__button">
										<svg class="departments__button-icon" width="18px" height="14px">
											<use xlink:href="images/sprite.svg#menu-18x14">
											</use>
										</svg>
										Menu <svg class="departments__button-arrow" width="9px" height="6px">
											<use xlink:href="images/sprite.svg#arrow-rounded-down-9x6">
											</use>
										</svg>
									</button>
								</div>
								<!-- .departments / end -->
							</div>
							<!-- .nav-links -->

							<!-- .nav-links / end -->
							<div class="nav-panel__indicators">

								<div class="indicator indicator--trigger--click">

									<?php
									$comp='0';
									$id_usuario=$_SESSION["id_usuario"];


									$sql = "SELECT * FROM carrito where id_usuario='$id_usuario' ";
									$result=$mysqli->query($sql);
									while ($columna = mysqli_fetch_array( $result ))
									{
										$comp=$comp+1;
									}
									?>
									<a href="carrito.php" class="indicator__button">
										<span class="indicator__area">
											<i class='fas fa-cart-arrow-down' style="font-size:20px;color: white;"></i>
											<span class="indicator__value">
												<?php echo$comp?></span>
											</span>
										</a>




										<div class="indicator__dropdown" style="max-height: 398px; overflow-y: auto;  box-shadow: 0 1px 15px rgba(0,0,0,.25);">
											<!-- .dropcart -->
											<div class="dropcart dropcart--style--dropdown">
												<div class="dropcart__body">
													<div class="dropcart__products-list">



														<?php

														$id_usuario=$_SESSION["id_usuario"];
														$total='0';
														$sql = "SELECT * FROM carrito where id_usuario='$id_usuario' ";
														$result=$mysqli->query($sql);
														while ($columna = mysqli_fetch_array( $result ))
														{
															$id_pro = $columna['id_producto'];
															$cantidad_pro = $columna['cantidad'];
															$precio_establecido = $columna['precio'];
															$color = $columna['color'];

															$sqlx = "SELECT * FROM productos where id='$id_pro' ";
															$resultx=$mysqli->query($sqlx);
															while ($columnax = mysqli_fetch_array( $resultx ))
															{
																$nombre_pro = strtolower($columnax['nombre']);
																$descripcion_pro =strtolower($columnax['descripcion']);
																$precio_u = $columnax['preciou'];
															}
															$sqll = "SELECT * FROM img where id_producto='$id_pro' ORDER BY id DESC ";
															$resultl=$mysqli->query($sqll);
															while ($columnal = mysqli_fetch_array( $resultl ))
															{
																$ruta = $columnal['ruta'];
																$a = $columnal['a'];

																$url=$ruta.$a;

																$ur = substr($url, 6);

															}

															$sub=$cantidad_pro*$precio_establecido; 
															$total=$total+$sub;

															$ran=rand(100000,999999);
															$ran2=rand(100000,999999);
															$var="Keyworkslinkre".$ran.$ran2.$id_pro;

															?>

															<div class="dropcart__product">
																<div class="product-image dropcart__product-image">
																	<a href="product.php?relsuperNova$=<?php echo$var?>" class="product-image__body">
																		<img class="product-image__img" src="<?php echo$ur?>" alt="">
																	</a>
																</div>
																<div class="dropcart__product-info">
																	<div class="dropcart__product-name">
																		<a href="product.php?relsuperNova$=<?php echo$var?>">
																			<?php echo ucfirst($nombre_pro)?></a>
																		</div>
																		<ul class="dropcart__product-options">
																			<li>
																				<?php echo ucfirst($descripcion_pro)?></li>

																			</ul>
																			<div class="dropcart__product-meta">
																				<span class="dropcart__product-quantity">
																					<?php echo$cantidad_pro?></span>
																					× <span class="dropcart__product-price">
																						$<?php echo$precio_establecido?></span>
																					</div>
																				</div>

																			</div>

																			<?php


																		}
																		?>






																	</div>




																	<div class="dropcart__totals">
																		<table>
																			<tbody>
																				<tr>
																					<th>
																					Subtotal</th>
																					<td>
																						$<?php echo$total.".00"?></td>
																					</tr>

																				</tbody>
																			</table>
																		</div>
																		<div class="dropcart__buttons">
												<!--<a class="btn btn-secondary" href="carrito.php">
												Ir al Carrito</a>-->
												<a class="btn btn-primary" href="carrito.php">
												Ir al Carrito</a>
											</div>
										</div>
									</div>
									<!-- .dropcart / end -->
								</div>
							</div>
							<div class="indicator indicator--trigger--click">
								<a href="" class="indicator__button">
									<span class="indicator__area">
										<i class='fas fa-user-alt' style="font-size: 20px;color: white;"></i>
									</span>
								</a>
								<div class="indicator__dropdown"style="max-height: 398px; overflow-y: auto;    box-shadow: 0 1px 15px rgba(0,0,0,.25);background: #fff;color:#3d464d;width: 280px;overflow-y: auto;overscroll-behavior-y: contain;">
									
									
									<div class="account-menu">
										
										<?php
										


										$id_usuario=$_SESSION["id_usuario"];



										if($id_usuario==NUll){


											?>
											












											<form  style="padding: 0 30px 32px;" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
												<div class="account-menu__form-title" style="text-align: center;padding: 30px 0 26px;font-weight: 700;">
													Iniciar Sesión
												</div>
												<div class="form-group">
													<label for="header-signin-email" class="sr-only">
														Correo
													</label>
													<input id="header-signin-email" type="email" name="correo" class="form-control form-control-sm" placeholder="Correo">
												</div>
												<div class="form-group">
													<label for="header-signin-password" class="sr-only">
														Contraseña
													</label>
													<div class="account-menu__form-forgot"  style="position: relative;">
														<input id="header-signin-password" type="password" class="form-control form-control-sm" placeholder="Contraseña" name="pass">
														<a href="recuperar.php" class="account-menu__form-forgot-link" style="position: absolute;top: 5px;bottom: 5px;border-radius: 2px;
														font-size: 12px;
														font-weight: 500;
														background: transparent;
														color: #3d464d;
														display: -ms-flexbox;
														display: flex;
														-ms-flex-align: center;
														align-items: center;
														padding: 0 7px;
														transition: background .1s,color .1s;
														right: 5px;">
														La olvidaste?
													</a>	
												</div>
											</div>
											<div class="form-group account-menu__form-button" style="    margin-top: 32px;text-align: center;">
												<button type="submit" class="btn btn-primary btn-sm">
													Ingresar
												</button>
											</div>
											<div class="account-menu__form-link" style="font-size: 14px;text-align: center;">
												<a href="account.php" style="color: #6c757d;
												transition: color .1s;">
												Crear Cuenta
											</a>
										</div>
									</form>
									
									















									<?php
								}
								?>
								<div class="account-menu__divider" style="height: 1px;background: #ebebeb;">
								</div>

								<?php
								$id_usuario=$_SESSION["id_usuario"]; 
								if($id_usuario!=NUll){

									
									header('Content-Type: text/html; charset=UTF-8');  
									$sql = "SELECT * FROM usuario where id='$id_usuario'";
									$result=$mysqli->query($sql);
									while ($columna = mysqli_fetch_array( $result ))
									{
										$apellido =$columna['apellido'];
										$nombrex = $columna['nombre'];
										$apellidox =$columna['apellido'];
										$correox = $columna['correo'];
										$l= $nombrex[0];
										
									}		


									?>	
									<?php 

									$ran=rand(100000,999999);
									$ran2=rand(100000,999999);
									$varr="Keyworkslinkre".$ran.$ran2;

									?>
									<a  style="display: -ms-flexbox;
									display: flex;
									-ms-flex-align: center;
									align-items: center;
									padding: 14px 20px;
									color: inherit;" href="perfil.php?ixdhgsdbbwhasdbnpsadhpasbndpi=<?php echo$varr.$id_usuario?>">
									<div class="account-menu__user-avatar" style="width: 44px;
									-ms-flex-negative: 0;
									flex-shrink: 0;
									margin-right: 14px;
									">
									
								</div>
								<div class="account-menu__user-info" style="display: -ms-flexbox;
								display: flex;
								-ms-flex-direction: column;
								flex-direction: column;
								-ms-flex-pack: center;
								justify-content: center;">
								<div class="account-menu__user-name" style="    font-size: 15px;
								line-height: 20px;
								font-weight: 500;">
								<?php echo $nombrex?><br>
								<?php echo $apellidox?>
							</div>
							<div class="account-menu__user-email" style="    font-size: 14px;
							line-height: 18px;
							color: #999;
							margin-top: 1px;">
							<?php echo$correox?>
						</div>
					</div>
				</a>
				<div class="account-menu__divider" style="height: 1px;
				background: #ebebeb;">
			</div>
			<style type="text/css">
		.search__input:hover::placeholder {
		    color: #ffffff
		}
		.search__input:hover~.search__border {
    background: transparent;
    box-shadow: inset 0 0 0 2px #673ab700
}
	</style>
			<style type="text/css">
				.account-menu__links a:hover {
					background: #f2f2f2;
				}
				a, a:hover {
					color: #d40112;
				}
			</style>
										<!--<ul class="account-menu__links" style="    list-style: none;
											padding: 12px 0;
											margin: 0;">
											<li>
												<a href="account-profile.html" style="display: block;
													color: inherit;
													font-size: 15px;
													padding: 5px 20px;
													font-weight: 500;">
													Edit Profile
												</a>
											</li>
											<li>
												<a href="account-orders.html " style="display: block;
													color: inherit;
													font-size: 15px;
													padding: 5px 20px;
													font-weight: 500;">
													Order History
												</a>
											</li>
											<li>
												<a href="account-addresses.html" style="display: block;
													color: inherit;
													font-size: 15px;
													padding: 5px 20px;
													font-weight: 500;">
													Addresses
												</a>
											</li>
											<li>
												<a href="account-password.html" style="display: block;
													color: inherit;
													font-size: 15px;
													padding: 5px 20px;
													font-weight: 500;">
													Password
												</a>
											</li>
										</ul>-->
										<div class="account-menu__divider" style="height: 1px;background: #ebebeb;">
										</div>
										<ul class="account-menu__links" style="    list-style: none;
										padding: 12px 0;
										margin: 0;">
										<li>
											<a href="close.php" style="    display: block;
											color: inherit;
											font-size: 15px;
											padding: 5px 20px;
											font-weight: 500;">
											Cerrar Sessión
										</a>
									</li>
								</ul>
								<?php

							}


							?>
							
						</div>
						







					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</div>
</div>
</header>

<!-- desktop site__header / end -->
<!-- site__body -->
<div class="site__body">
	<div class="page-header">
		<div class="page-header__container container">
			<div class="page-header__breadcrumb">
				
			</div>
			<div class="page-header__title" >
				<h1 >
				Lista de pedidos </h1>
				<a href="historial.php" style="color:#ec7b71;">Ver Historial de pedidos <i class="fas fa-file-pdf" ></i></a>
			</div>
		</div>
	</div>
	<div id="productos-carrito"></div>
	<div class="cart block">
		<div class="container">
			<table class="cart__table cart-table">
				<thead class="cart-table__head" style="background: #ec7b71;color: white;">
					<tr class="cart-table__row">
						<th class="cart-table__column cart-table__column--price" style="display:none;">
						Imagen</th>

						<th class="cart-table__column cart-table__column--price" style="text-align: center;">
						Imagen</th>
						<th class="cart-table__column cart-table__column--product" style="text-align: center;">
						Producto</th>
						
						<th class="cart-table__column cart-table__column--price" style="text-align: center;">
						Precio</th>
						<th class="cart-table__column cart-table__column--quantity" style="text-align:center;">
						Cantidad</th>
						<th class="cart-table__column cart-table__column--total" style="text-align: center;">
						Total</th>
						
					</tr>
				</thead>
				<tbody class="cart-table__body" style="background: #ffffff;" >
					<?php

						if ($id_usuario!='7586' and $id_usuario!='7659' and $id_usuario!='7610' ) {
							//PrecioDescuento($id_usuario);
						}

						
						




						/*
							Refactorizamos consulta, para traer de una vez la imagen del producto, y stock de almacen
						*/
						$sql = "SELECT c.*, p.almacen,p.minimo, ( SELECT i.a FROM img i WHERE i.id_producto = c.id_producto LIMIT 1 ) AS imagen FROM carrito c INNER JOIN productos p ON p.id = c.id_producto WHERE c.id_usuario = ".$id_usuario."";
						$result=$mysqli->query($sql);
						while ($columna = mysqli_fetch_array( $result ))
						{

							$id_producto=$columna['id_producto'];
							$nombre=$columna['nombre'];
							$codigo=$columna['codigo'];
							$precio=$columna['precio'];
							$cantidad=$columna['cantidad'];
                            $stock = intval($columna['almacen']);
							$minimo = intval($columna['minimo'])+intval(intval($columna['minimo'])*0.10);

							//eliminamos una nueva consulta de imagen 
							// $sqll = "SELECT * FROM img where id_producto='$id_producto' ORDER BY id ASC limit 1";
							// $resultl=$mysqli->query($sqll);
							// while ($columnal = mysqli_fetch_array( $resultl )){
								
							// 	$a = $columnal['a'];
							// 	$url="imagenes/".$id_producto."/".$a;
							// }
							$image = $columna['imagen'];
							$url_image = "imagenes/".$id_producto."/".$image;
							$ran=rand(100000,999999);
							$ran2=rand(100000,999999);
							$var="Keyworkslinkre".$ran.$ran2.$id_producto;
							?>
								<tr class="cart-table__row" >
									<td class="cart-table__column cart-table__column--image" style="text-align: center;">
										<a >
											<img src="<?php echo $url_image?>" alt="">
										</a>
									</td>
									<td class="cart-table__column cart-table__column--product" style="text-align: center;">

										<a href="product.php?relsuperNova$=<?php echo$var?>" class="cart-table__product-name">
											<?php echo ucfirst($nombre)?>
										</a> 
										<br>
										<?php 
											if($cantidad  <= $stock && $stock <= $minimo){
											    ?><span style="font-size:12px;font-style:italic;color:red;">Quedan pocas piezas disponible</span>
											<?php
											}

											if($cantidad > $stock){
												?><span style="font-size:12px;font-style:italic;color:red;">No queda suficiente stock, comunicate con un asesor</span>
											<?php
											}
										?>
									</td>

									<td class="cart-table__column cart-table__column--price" data-title="Precio" style="text-align: center;">
											<?php echo money_format('%.2n',$precio)?>
									</td>





										<td class="cart-table__column cart-table__column--quantity" data-title="Quantity" style="text-align: center;">
											
												<div class="input-number" >
													<input class="form-control input-number__input" type="number" name="actualizar" min="1"  value="<?php echo$cantidad?>" id="id<?php echo$id_producto?>" >

													<div class="input-number__add" >
													</div>
													<div class="input-number__sub" >
													</div>


												</div>
												<input type="hidden" name="id_actualizar" value="" >
												<button type="button" class="btn btn-light btn-sm btn-svg-icon" style="width: 100%;" onclick="Actualizar('<?php echo$id_producto?>','<?php echo$id_usuario?>')">Actualizar</button>



									

										</td>













										<td class="cart-table__column cart-table__column--total" data-title="Total" style="text-align: center;">
											<?php echo money_format('%.2n',$cantidad*$precio)?>
											<a onclick="Eliminar('<?php echo$id_producto?>','<?php echo$id_usuario?>')" style="cursor: pointer;">
												<i class='far fa-trash-alt' style="color: red;"></i>
											</a>

										</td>

									</tr>
								<?php
							}
							$sqltt = "SELECT sum(cantidad*precio) as total_pagar FROM carrito where id_usuario='$id_usuario' ";
						$resulttt=$mysqli->query($sqltt);
						while ($columnatt = mysqli_fetch_array( $resulttt ))
						{
							$total_pagar=$columnatt['total_pagar'];
						}
					?>
				</tbody>
			</table>
				<br>

				<style>
					/* The container */
					.containerr {
						display: block;
						position: relative;
						padding-left: 35px;
						margin-bottom: 12px;
						cursor: pointer;
						font-size: 22px;
						-webkit-user-select: none;
						-moz-user-select: none;
						-ms-user-select: none;
						user-select: none;
					}

					/* Hide the browser's default radio button */
					.containerr input {
						position: absolute;
						opacity: 0;
						cursor: pointer;
					}

					/* Create a custom radio button */
					.checkmark {
						position: absolute;
						top: 0;
						left: 0;
						height: 25px;
						width: 25px;
						background-color: #eee;
						border-radius: 50%;
					}

					/* On mouse-over, add a grey background color */
					.containerr:hover input ~ .checkmark {
						background-color: #ccc;
					}

					/* When the radio button is checked, add a blue background */
					.containerr input:checked ~ .checkmark {
						background-color: #2196F3;
					}

					/* Create the indicator (the dot/circle - hidden when not checked) */
					.checkmark:after {
						content: "";
						position: absolute;
						display: none;
					}

					/* Show the indicator (dot/circle) when checked */
					.containerr input:checked ~ .checkmark:after {
						display: block;
					}

					/* Style the indicator (dot/circle) */
					.containerr .checkmark:after {
						top: 9px;
						left: 9px;
						width: 8px;
						height: 8px;
						border-radius: 50%;
						background: white;
					}
				</style>

				






				<div class="row justify-content-start pt-5" >
					<div class="col-11 col-md-7 col-lg-6 col-xl-5">
						<div class="card" style="border-radius: 10px;box-shadow: 0px 3px 1px 0px #aaaaaa;">
							<form action="mipdf/index2.php" method="POST" name="tuformulario" >
								<input type="hidden" name="id_usuario_compra" value="<?php echo$id_usuario?>">
								<div class="card-body">

									<table class="cart__totals" style="margin-bottom: 10px">
										<tr>
											<div id="myRadioGroup">
												<?php
												$id_usuario=$_SESSION["id_usuario"];
												$sqlde = "SELECT * FROM destinatario where id_usuario='$id_usuario' ";
												$resultde=$mysqli->query($sqlde);
												while ($columnade = mysqli_fetch_array( $resultde ))
												{
													$nombre_des = $columnade['nombre'];
													$apellido_des = $columnade['apellido'];
													$direccion_des = $columnade['direccion'];
													$colonia_des = $columnade['colonia'];
													$ciudad_des = $columnade['ciudad'];
													$estado_des = $columnade['estado'];
													$codigop_des = $columnade['codigop'];
													$telefono_des = $columnade['telefono'];
												}
												?>
												<center style="margin-bottom:-10px;"><label><p><b>Datos del Cliente</b></p></label></center>
												<p style="text-align: start;margin-bottom: 0rem;">Nombre:</p>	
												<input type="text" name="nombre" id="nombre" value="<?php echo$nombre_des?>" autocomplete="OFF"class="form-control" ><p></p>
												<p style="text-align: start;margin-bottom: 0rem;">Apellido:</p>
												<input type="text" name="apellido" id="apellido" value="<?php echo$apellido_des?>" autocomplete="OFF" class="form-control"><p></p>
												<p style="text-align: start;margin-bottom: 0rem;">Dirección:</p>
												<input type="text" name="direccion"id="direccion" value="<?php echo$direccion_des?>" autocomplete="OFF" class="form-control"><p></p>
												<p style="text-align: start;margin-bottom: 0rem;">Colonia:</p>
												<input type="text" name="colonia" id="colonia" value="<?php echo$colonia_des?>" autocomplete="OFF" class="form-control"><p></p>
												<p style="text-align: start;margin-bottom: 0rem;">Ciudad:</p>
												<input type="text" name="ciudad" id="ciudad" value="<?php echo$ciudad_des?>" autocomplete="OFF" class="form-control"><p></p>
												<p style="text-align: start;margin-bottom: 0rem;">Estado:</p>
												<select name="estado" required="yes" class="form-control" id="estado">
													<option value="<?php echo$estado_des?>" ><?php echo$estado_des?></option>
													<option value="Aguascalientes">Aguascalientes</option>
													<option value="Baja California">Baja California</option>
													<option value="Baja California Sur">Baja California Sur</option>
													<option value="Campeche">Campeche</option>
													<option value="Chiapas">Chiapas</option>
													<option value="Chihuahua">Chihuahua</option>
													<option value="Ciudad de México">Ciudad de México</option>
													<option value="Coahuila">Coahuila</option>
													<option value="Colima">Colima</option>
													<option value="Durango">Durango</option>
													<option value="Estado de México">Estado de México</option>
													<option value="Guanajuato">Guanajuato</option>
													<option value="Guerrero">Guerrero</option>
													<option value="Hidalgo">Hidalgo</option>
													<option value="Jalisco">Jalisco</option>
													<option value="Michoacán">Michoacán</option>
													<option value="Morelos">Morelos</option>
													<option value="Nayarit">Nayarit</option>
													<option value="Nuevo León">Nuevo León</option>
													<option value="Oaxaca">Oaxaca</option>
													<option value="Puebla">Puebla</option>
													<option value="Querétaro">Querétaro</option>
													<option value="Quintana Roo">Quintana Roo</option>
													<option value="San Luis Potosí">San Luis Potosí</option>
													<option value="Sinaloa">Sinaloa</option>
													<option value="Sonora">Sonora</option>
													<option value="Tabasco">Tabasco</option>
													<option value="Tamaulipas">Tamaulipas</option>
													<option value="Tlaxcala">Tlaxcala</option>
													<option value="Veracruz">Veracruz</option>
													<option value="Yucatán">Yucatán</option>
													<option value="Zacatecas">Zacatecas</option>
												</select><p></p>
												<p style="text-align: start;margin-bottom: 0rem;">C.P:</p>
												<input type="text" name="codigop" id="codigop" value="<?php echo$codigop_des?>" autocomplete="off" class="form-control"><p></p>
												<p style="text-align: start;margin-bottom: 0rem;">Telf:</p>
												<input type="text" name="telefono" id="telefono" value="<?php echo$telefono_des?>" autocomplete="off" class="form-control">
												<br><br>


												<label class="containerr">Entrega Personal
													<input type="radio" checked="checked" name="check" value="No" onclick="Mostrar('1');">
													<span class="checkmark"></span>
												</label>
												
												

												<label class="containerr">Enviar mercancia
													<input type="radio" name="check" value="Si" onclick="Mostrar('0');">
													<span class="checkmark"></span>
												</label>


												<div id="CarsSi" class="desc" style="display: none;background: cornsilk;">
													<div style="padding: 10px;">
														<center style="margin-bottom:-10px;"><label><p><b>Datos del Envio</b></p></label></center>
														<p class="mt-5" style="margin-top: 20px;margin-bottom: 0rem;">
															<strong>Es obligatorio el RFC para todas las paqueterias excepto para FEDEX y ESTAFETA</strong>
														</p>
														<div class="form-group">
															<input type="text" name="rfc" class="form-control" id="rfc" oninput="calculate();" required placeholder="RFC ¡Importante!" autocomplete="OFF" maxlength="13" minlength="12">

															<!--<div style="display: flex;justify-content: end;">
																<button class="btn btn-primary  btn-block cart__checkout-button" type=button onclick="document.getElementById('rfc').value = 'XAXX010101000';" style="height: 32px;font-size: 0.9rem;margin-top: 10px;width: 160px;padding: 0rem 0rem;">
																	NO POSEO RFC <i class='fas fa-exclamation-circle'></i>
																</button>
															</div> -->

														</div>

														<SELECT name="envio" class="form-control" id="envio">
															<option value="ESTAFETA">ESTAFETA</option>
															<option value="FEDEX">FEDEX</option>
															<option value="DHL">DHL</option>
															<option value="EVISA">EVISA</option>
															<option value="TRAVISA">TRAVISA</option>
															<option value="CASTORES">CASTORES</option>
															<option value="LINEAS DEL SUR">LINEAS DEL SUR</option>
															<option value="EL DUERO">EL DUERO</option>

														</SELECT>
														<br>
													</div>

													


												</div>	


												


												

												<script type="text/javascript">
													function Mostrar(valor) {
														if (valor==1){
															mostrar.style.display="block";
															mostrar2.style.display="none";
														}else
														if (valor==0){
															mostrar.style.display="none";
															mostrar2.style.display="block";
														}
													}
												</script>



												




											</div>
											<input id="vuelto" type="hidden" style="border:8px;width:280px;text-align:end;color:#009700;" value="No">
											<input  type="hidden" style="border:8px;width:280px;text-align:end;color:#009700;" name="checkk" value="Si">
											<script type="text/javascript">
												$(document).ready(function() {
													$("input[name$='check']").click(function() {
														var test = $(this).val();
														$("div.desc").hide();
														$("div.desc2").hide();
														$("#Cars" + test).show();
														$("div.desc2").show();
														document.getElementById('vuelto').value = test;
													});
												});
											</script>

										</tr>
									</table>	







									<table class="cart__totals">





										<tfoot class="cart__totals-footer">
											<tr>
												<th>
													Total
												</th>
												<td>
													<?php echo money_format('%.2n',$total_pagar)?></td>
												</tr>
											</tfoot>
										</table>
										<?php
										if (isset($cantidad_pro)) {
											?>
											<button class="btn btn-primary btn-xl btn-block cart__checkout-button" type=button onclick="pregunta()" readonly >
												Procesar su pedido
											</button> 
											<?php
										}
										?>
										<script language="JavaScript">
											function pregunta(){
												
												var confirmar = document.getElementById('vuelto').value;  
												if (confirmar=='Si'){
													var rfcc = document.getElementById('rfc').value;
													var envio = document.getElementById('envio').value;
													var nombre = document.getElementById('nombre').value;
													var apellido = document.getElementById('apellido').value;
													var direccion = document.getElementById('direccion').value;
													var colonia = document.getElementById('colonia').value;
													var ciudad = document.getElementById('ciudad').value;
													var estado = document.getElementById('estado').value;
													var codigop = document.getElementById('codigop').value;
													var telefono = document.getElementById('telefono').value;

													if (envio!='' && nombre!='' && apellido!='' && direccion!='' && colonia!='' && ciudad!='' && estado!='' && codigop!='' && telefono!=''){
														

														var numeroCaracteres = rfcc.length;

														

														alertify.confirm("<center><div style='color:#ff2512;'margin-bottom: 8px;><b>Terminos y condiciones de compras</b></div></center><div style='font-size: 11px;'><b><div style='margin-bottom: 5px;'>- Compra minima recomendada <font color='#ff2512'><b>$1000</b></font> en mercancía.</div><div style='margin-bottom: 5px;'>- No realizar transacciones hasta que se le proporcione el número de <font color='#ff2512'><b>cuenta correspondiente</b></font>.</div><div style='margin-bottom: 5px;'>- Colocar su <font color='#ff2512'><b>nombre y apellido</b></font> en el (motivo,concepto ó referencia) de su transacción para identificarla.</div><div style='margin-bottom: 5px;'>- Prohibido realizar depositos en <font color='#ff2512'><b>OXXO</b></font> y cadenas similares.</div><div style='margin-bottom: 5px;'>- Realizamos el envío de su pedido si asi lo requiere, garantizamos el embalaje y seguridad del mismo <font color='#ff2512'><b>hasta ser entregado a la paquetería de envíos</b></font>.</div><div style='margin-bottom: 5px;'><font color='#ff2512'><b>- Aviso IMPORTANTE!</b></font> Todos los envios entregados a paquetería, quedara bajo la responsabilidad del <font color='#ff2512'>CLIENTE</font> y de la misma <font color='#ff2512'>PAQUETERÍA</font>. </div><div style='margin-bottom: 5px;'>- Al momento de requerir envío, recuerde depositar el monto que se le mencione <font color='#ff2512'><b>junto con el monto de la mercancía</b></font>.</div></b></div><center><div style='color:#ff2512;'margin-bottom: 8px;font-size: 12px;'><b>IMPORTADORA SUPERNOVA S.A DE C.V</b></div></center>",
															function(){
																document.tuformulario.submit()
															},
															function(){
																alertify.error('Cancelado');
															});
														



													}else{
														
														
														alertify.error('Faltan campos por llenar');

														var ch = 0;
														if (nombre == '' && ch == 0) {
															document.getElementById( "nombre" ).focus();
															ch=1;
														}
														if (apellido == '' && ch == 0) {
															document.getElementById( "apellido" ).focus();
															ch=1;
														}
														if (direccion == '' && ch == 0) {
															document.getElementById( "direccion" ).focus();
															ch=1;
														}
														if (colonia == '' && ch == 0) {
															document.getElementById( "colonia" ).focus();
															ch=1;
														}
														if (ciudad == '' && ch == 0) {
															document.getElementById( "ciudad" ).focus();
															ch=1;
														}
														if (estado == '' && ch == 0) {
															document.getElementById( "estado" ).focus();
															ch=1;
														}
														if (codigop == '' && ch == 0) {
															document.getElementById( "codigop" ).focus();
															ch=1;
														}
														if (telefono == '' && ch == 0) {
															document.getElementById( "telefono" ).focus();
															ch=1;
														}


													}
													
												}else{
													
													var nombre = document.getElementById('nombre').value;
													var apellido = document.getElementById('apellido').value;
													var direccion = document.getElementById('direccion').value;
													var colonia = document.getElementById('colonia').value;
													var ciudad = document.getElementById('ciudad').value;
													var estado = document.getElementById('estado').value;
													var codigop = document.getElementById('codigop').value;
													var telefono = document.getElementById('telefono').value;

													if (nombre!='' && apellido!='' && direccion!='' && colonia!='' && ciudad!='' && estado!='' && codigop!='' && telefono!=''){
														alertify.confirm("<center><div style='color:#ff2512;'margin-bottom: 8px;><b>Terminos y condiciones de compras</b></div></center><div style='font-size: 11px;'><b><div style='margin-bottom: 5px;'>- Compra minima recomendada <font color='#ff2512'><b>$1000</b></font> en mercancía.</div><div style='margin-bottom: 5px;'>- No realizar transacciones hasta que se le proporcione el número de <font color='#ff2512'><b>cuenta correspondiente</b></font>.</div><div style='margin-bottom: 5px;'>- Colocar su <font color='#ff2512'><b>nombre y apellido</b></font> en el (motivo,concepto ó referencia) de su transacción para identificarla.</div><div style='margin-bottom: 5px;'>- Prohibido realizar depositos en <font color='#ff2512'><b>OXXO</b></font> y cadenas similares.</div><div style='margin-bottom: 5px;'>- Realizamos el envío de su pedido si asi lo requiere, garantizamos el embalaje y seguridad del mismo <font color='#ff2512'><b>hasta ser entregado a la paquetería de envíos</b></font>.</div><div style='margin-bottom: 5px;'><font color='#ff2512'><b>- Aviso IMPORTANTE!</b></font> Todos los envios entregados a paquetería, quedara bajo la responsabilidad del <font color='#ff2512'>CLIENTE</font> y de la misma <font color='#ff2512'>PAQUETERÍA</font>. </div><div style='margin-bottom: 5px;'>- Al momento de requerir envío, recuerde depositar el monto que se le mencione <font color='#ff2512'><b>junto con el monto de la mercancía</b></font>.</div></b></div><center><div style='color:#ff2512;'margin-bottom: 8px;font-size: 12px;'><b>IMPORTADORA SUPERNOVA S.A DE C.V</b></div></center>",
															function(){
																document.tuformulario.submit()
															},
															function(){
																alertify.error('Cancelado');
															});
													}else{
														
														
														alertify.error('Faltan campos por llenar');

														var ch = 0;
														if (nombre == '' && ch == 0) {
															document.getElementById( "nombre" ).focus();
															ch=1;
														}
														if (apellido == '' && ch == 0) {
															document.getElementById( "apellido" ).focus();
															ch=1;
														}
														if (direccion == '' && ch == 0) {
															document.getElementById( "direccion" ).focus();
															ch=1;
														}
														if (colonia == '' && ch == 0) {
															document.getElementById( "colonia" ).focus();
															ch=1;
														}
														if (ciudad == '' && ch == 0) {
															document.getElementById( "ciudad" ).focus();
															ch=1;
														}
														if (estado == '' && ch == 0) {
															document.getElementById( "estado" ).focus();
															ch=1;
														}
														if (codigop == '' && ch == 0) {
															document.getElementById( "codigop" ).focus();
															ch=1;
														}
														if (telefono == '' && ch == 0) {
															document.getElementById( "telefono" ).focus();
															ch=1;
														}


													}
												}



												
											}
										</script>
									</div>
								</form>
							</div>
						</div>
					</div>

				</div>
			</div>
		</div>
		<!-- site__body / end -->
		<!-- site__footer -->

		<!-- site__footer / end -->
	</div>
<footer class="site__footer"style="text-align: center;" >
   <div class="site-footer">
      <div class="container">
         <div class="site-footer__widgets" style="    padding: 0px 0 42px;">
            <div class="row">
               <div class="col-12 col-md-6 col-lg-4" style="flex: 0 0 100%;max-width: 100%;" >
                  <div class="site-footer__widget footer-contacts">
                     
                     <ul class="footer-contacts__contacts">
                        <a href="https://www.facebook.com/ImportadoraSupernova" target="_blank"><img src="img//Primavera/facebook.png" style="width: 60px;"></a>
                        <a href="https://www.instagram.com/lemussaoficial/" target="_blank"><img src="img//Primavera/instagram.png" style="width: 60px;"></a>
                        <a href="https://www.tiktok.com/@lemussaoficial?"target="_blank"><img src="img//Primavera/tiktok.png" style="width: 60px;"></a>
                        <a href="https://www.youtube.com/@lemussaoficial3601/featured"target="_blank"><img src="img//Primavera/youtube.png" style="width: 60px;"></a>
                        <a href="https://wa.me/+525524954670"  target="_blank"><img src="img//Primavera/whatsap.png" style="width: 60px;"></a>
                        <li style="margin-top:25px">
                           <i class="footer-contacts__icon far fa-envelope"></i>
                           Correo: <a href="mailto:importadorasupernova@gmail.com">importadorasupernova@gmail.com</a>
                        </li>
                        <li>
                           <i class="footer-contacts__icon fas fa-mobile-alt"></i>
                           Soporte al cliente: <a href="tel:+5215549311058" style="color: #0a4740;">+52 1 55 4931 1058</a>
                        </li>
                        <li>
                           <i class="footer-contacts__icon fas fa-mobile-alt"></i>
                           Pedidos para envios a nivel nacional: <a href="tel:+5215545564443" style="color: #0a4740;">+52 1 55 4556-4443</a>
                        </li>
                        <li>
                           <i class="footer-contacts__icon fas fa-mobile-alt"></i>
                           Pedidos para entregas en CDMX: <a href="tel:+5215579978519" style="color: #0a4740;">+52 1 55 7997-8519</a>
                        </li>
                        <li>
                           <i class="footer-contacts__icon fas fa-globe-americas"></i>
                           Dirección 1: Calle manuel de la peña y peña plaza #13 planta baja, locales 22 y 23.
                        </li>
                        <li>
                           <i class="footer-contacts__icon fas fa-globe-americas"></i>
                           Dirección 2: Calle manuel de la peña y peña plaza #18 planta baja, locales A5 y A6.
                        </li>
                        <li>
                           <i class="footer-contacts__icon fas fa-globe-americas"></i>
                           Dirección 3: Calle nicaragua #51, central de mayoreo, planta baja, local 5.
                        </li>
                        <li>
                           <i class="footer-contacts__icon fas fa-globe-americas"></i>
                            Dirección 4: Distribuidor oficial: Plaza izazaga 89, planta baja local 37-39
                        </li>
                        <li>
                           <i class="footer-contacts__icon fas fa-globe-americas"></i>
                            Dirección 5: Calle Republica de colombia 79, Plaza Carmen #51, planta baja , local 23 y 24.
                        </li>
                     </ul>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</footer>

	
	<?php
	if ($_SESSION['id_usuario']==NULL) {
		echo"<script>
		function miFuncion() {
			alertify.alert('Debe ingresar como usuario.', function(){
				window.location.href='https://www.importadorasupernova.com';
				});

			}
			window.onload=miFuncion;
			</script>";
		}
		mysqli_close($mysqli);
		


		?>



	<script type="text/javascript">


		function Actualizar(id,id_usuario) {
			var cantidad = document.getElementById("id"+id).value;
			
			$.ajax({                        
                          type: "POST",                 
                          url: "Funcion-ofertas/actualizar-producto.php",                     
                          data: {"id_usuario":id_usuario,"id_producto":id,"cantidad":cantidad}, 
                          success: function(data)             
                          {  

                            

                            location.reload();
                            
                       }
            });

		}






		function Eliminar(id,id_usuario) {





			$.ajax({                        
                          type: "POST",                 
                          url: "Funcion-ofertas/eliminar-producto.php",                     
                          data: {"id_usuario":id_usuario,"id_producto":id}, 
                          success: function(data)             
                          {  

                            

                            location.reload();
                            
                       }
            });
		}






		
	</script>
	</body>


	</html>
