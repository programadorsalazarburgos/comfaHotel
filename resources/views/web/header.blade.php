<section id="header" class="header">
	<div class="container-fluid">
		<div class="float-left" style="margin-top: 8px;">
			<img src="{{ asset('img/logo.png') }}" alt="image2" width="110px" height="51px" />
		</div><!-- /.logo -->
		@if(auth()->check())
		<div class="menu-extra">
		<div class="box-search">
			<span class="icon_search"></span>
				<form action="{{ route('tienda.buscar') }}" method="get" accept-charset="utf-8">
					<div class="input-search">
						<input type="text" name="search" placeholder="¿Qué desea buscar?">
						<span class="ti-close delete"></span>
					</div>
				</form>
			</div>
			<div class="box-canvas">
				<span class="ti-shopping-cart" id="shopping-cart">
					<i class="numb-cart">{{ \Cart::count() }}</i>
				</span>						
			</div>
		</div>
		<div class="nav-wrap">
			<div class="btn-menu">
		        <span></span>
		    </div><!-- //mobile menu button -->
			<div id="mainnav" class="mainnav">
				<ul class="menu">
					<li class="has-menu-mega">
						<a href="{{ route('main') }}" title="Home">
							Inicio
						</a>
					</li><!-- /.has-menu-mega -->
					<li class=" has-submenu">
						<a href="{{ route('tienda') }}" title="Productos">
							Productos
						</a>
					</li><!-- /.has-submenu -->
					
						<li class=" has-submenu">
						<a href="{{ route('miscompras') }}" title="Mis Compras">
							Mis Compras
						</a>
					</li><!-- /.has-submenu -->
				</ul><!-- /.menu -->
			</div><!-- /.mainnav -->
		</div><!-- /.nav-wrap -->
		@endif
		
		
		<ul class="flat-unstyled float-right">
			@if(auth()->check())
			<li class="login">
				<a href="#" title="Cuenta"> 
					<i class="ti-user"></i> 
					{{ auth()->user()->usuario }}

					<i class="fa fa-angle-down" aria-hidden="true"></i>
				</a>
				<div style="display: none;">
			     {{ 
				$sald = App\Saldo::select('saldo')->where('cliente_id', '=', Auth::id())->firstOrfail() 	
			     }}
				</div>
				<ul class="unstyled border-radius-5 box-shadow">
					<li><a href="{{ route('logout') }}" 
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Cerrar Sesión</a></li>
                    <li><a href="#">Saldo: {{  $sald->saldo }}</a></li>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        {{ csrf_field() }}
                    </form>
				</ul>
			</li>
			@else
			<li class="login">
				<a href="{{ route('login') }}" title="">Iniciar Sesión</a>
			</li>
			@endif
		</ul>
		<div class="clearfix"></div>
	</div><!-- /.container-fluid -->
	<div class="menu-canvas">
		<div class="close">
			<i class="fa fa-times" aria-hidden="true"></i>
		</div>
		<div class="box-cart">
			Cantidad de productos: <span class="count-products">{{ \Cart::count() }}</span> 
			<span class="icon_bag_alt"> 
			</span>
			<div class="subcart">
				<ul class="product-choose">
					@forelse(\Cart::content() as $row)
					<li class="overflow">
						<div class="img-product float-left">
							<img src="{{ asset('web/images/shop/s-01.jpg') }}" alt="">
						</div>
						<div class="info-product float-left overflow">
							<div class="price">
								${{ $row->price }} x
								<span class="mount mount-subtraction">
									<a href="{{ route('tienda.carrito.editar', $row->rowId) }}" data-qty="{{ $row->qty }}">
										<i class="icon_minus_alt2"></i>
									</a>
								</span>
								<span class="unit-total">{{ $row->qty }}</span>
								<span class="mount mount-plus">
									<a href="{{ route('tienda.carrito.editar', $row->rowId) }}" data-qty="{{ $row->qty }}">
										<i class="icon_plus_alt2"></i>
									</a>
								</span> 
							</div>
							<div class="name">
								<a href="{{ route('tienda.carrito.agregar', $row->id) }}" title="">{{ $row->name }}</a>
							</div>
						</div>
						<div class="delete">
							<a class="delete-product-cart" href="{{ route('tienda.carrito.editar', $row->rowId) }}"><i class="icon_close"></i></a>
						</div>
					</li>
					@empty
					@endforelse

				</ul>
				<div class="total-cart overflow">
					<p class="float-left">TOTAL:</p>
					<p class="float-right">${{ \Cart::total() }}</p>
				</div>
				<div class="btn-cart">
					<a href="{{ route('tienda.carrito.guardar') }}" class="check-out" title="">Comprar</a>
					<a href="{{ route('tienda.carrito.vaciar') }}" class="view-cart" title="">Vaciar</a>
				</div>
			</div>
		</div>
		<div class="share-link">
			<ul class="social-ft">
				<li>
					<a href="" title="Facebook">
						<i class="ti-facebook" aria-hidden="true"></i>
					</a>
				</li>
				<li>
					<a href="" title="Twitter">
						<i class="ti-twitter-alt" aria-hidden="true"></i>
					</a>
				</li>
				<li>
					<a href="" title="Google Plus">
						<i class="ti-google" aria-hidden="true"></i>
					</a>
				</li>
				<li>
					<a href="" title="Instagram">
						<i class="fa fa-instagram" aria-hidden="true"></i>
					</a>
				</li>
				<li>
					<a href="" title="Dribble">
						<i class="ti-dribbble" aria-hidden="true"></i>
					</a>
				</li>
				<li>
					<a href="" title="Pinterest">
						<i class="ti-pinterest" aria-hidden="true"></i>
					</a>
				</li>
			</ul>
			<p class="copyright" href="http://www.facebook.com/jcascantealfaro" >Copyright ©2018 DeveloperCoco. All Rights Reserved</p>
		</div>
	</div>
</section><!-- /.header -->