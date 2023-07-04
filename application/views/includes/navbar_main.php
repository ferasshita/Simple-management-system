<?php
if (is_dir('imgs/')) {
	$dircheckPath = '';
} elseif (is_dir('../imgs/')) {
	$dircheckPath = '../';
} elseif (is_dir('../../imgs/')) {
	$dircheckPath = '../../';
}
?>
<style>
	.ribbon {
		position: absolute;
		right: var(--right, 10px);
		top: var(--top, -3px);
		filter: drop-shadow(2px 3px 2px rgba(0, 0, 0, 0.5));
	}

	.ribbon > .content {
		color: white;
		font-size: 1px;
		text-align: center;
		font-weight: 100;
		background: var(--color, #2ca7d8) linear-gradient(45deg, rgba(0, 0, 0, 0) 0%, rgba(255, 255, 255, 0.25) 100%);
		padding: 8px 2px 4px;
		clip-path: polygon(0 0, 100% 0, 100% 100%, 50% 100%, 0 100%);
		width: var(--width, 32px);
		min-height: var(--height, 36px);
		transition: clip-path 1s, padding 1s, background 1s;
	}

	.ribbon.slant-up > .content {
		clip-path: polygon(0 0, 100% 0, 100% calc(100% - 12px), 50% calc(100% - 6px), 0 100%);
	}

	.ribbon.slant-down > .content {
		clip-path: polygon(0 0, 100% 0, 100% 100%, 50% calc(100% - 6px), 0 calc(100% - 12px));
	}

	.ribbon.down > .content {
		clip-path: polygon(0 0, 100% 0, 100% calc(100% - 8px), 50% 100%, 0 calc(100% - 8px));
	}

	.ribbon.up > .content {
		clip-path: polygon(0 0, 100% 0, 100% 100%, 50% calc(100% - 8px), 0 100%);
	}

	.ribbon.check > .content {
		clip-path: polygon(0 0, 100% 0, 100% calc(100% - 20px), 40% 100%, 0 calc(100% - 12px));
	}
</style>
<header class="main-header">
	<div class="d-flex align-items-center logo-box pl-20">
		<a href="javascript:void(0)" class="waves-effect waves-light nav-link rounded d-none d-md-inline-block push-btn"
		   data-toggle="push-menu" role="button">
			<img src="<?php echo "$dircheckPath"; ?>imgs/main_icons/svg-icon/collapse.svg" class="img-fluid svg-icon"
				 alt="">
		</a>
		<!-- Logo -->
		<a href="<?php echo base_url(); ?>Dashboard" class="logo">
			<!-- logo-->
			<div class="logo-lg">
				<span class="light-logo"><img src="<?php echo "$dircheckPath"; ?>imgs/main_icons/logo-dark-text.png"
											  alt="logo"></span>
				<span class="dark-logo"><img src="<?php echo "$dircheckPath"; ?>imgs/main_icons/logo-light-text.png"
											 alt="logo"></span>
			</div>
		</a>
	</div>
	<!-- Header Navbar -->
	<nav class="navbar navbar-static-top pl-10">
		<!-- Sidebar toggle button-->
		<div class="app-menu">
			<ul class="header-megamenu nav">
				<li class="btn-group nav-item d-md-none">
					<a href="javascript:void(0)" class="waves-effect waves-light nav-link rounded push-btn"
					   data-toggle="push-menu" role="button">
						<img src="<?php echo "$dircheckPath"; ?>imgs/main_icons/svg-icon/collapse.svg"
							 class="img-fluid svg-icon" alt="">
					</a>
				</li>
				<li class="btn-group nav-item">
					<a href="javascript:void(0)" data-provide="fullscreen"
					   class="waves-effect waves-light nav-link rounded full-screen" title="Full Screen">
						<img src="<?php echo "$dircheckPath"; ?>imgs/main_icons/svg-icon/fullscreen.svg"
							 class="img-fluid svg-icon" alt="">
					</a>
				</li>
				<li class="btn-group d-md-inline-flex d-none">
					<div class="search-bx ml-10">
						<div class="input-group">
							<input type="number" class="form-control fix-bold" oninput="exch_rat();"
								   onkeypress="div_mul()" onkeyup="conve()"
								   value="<?php echo $_SESSION['exchange_rate']; ?>"
								   placeholder="<?php echo lang('price'); ?>" autocomplete="off" inputmode="decimal"
								   aria-label="Search" id="exch_input" aria-describedby="button-addon2">

						</div>
					</div>
				</li>
			</ul>
		</div>

		<div class="navbar-custom-menu r-side">
			<ul class="nav navbar-nav">


				<?php //if($_SESSION['type'] == "boss"){ ?>
				<!--                <li class="dropdown user user-menu">-->
				<!--                    <a href="packages" class="waves-effect waves-light dropdown-toggle" title="Packages">-->
				<!--                        <img src="-->
				<?php //echo"$dircheckPath"; ?><!--imgs/main_icons/svg-icon/pages.svg" class="rounded svg-icon" alt="" />-->
				<!--                    </a>-->
				<!--                </li>-->
				<?php //} ?>
			<?php if($_SESSION['package'] == "0"){ ?>	<li class="dropdown user user-menu">

	 <a href="<?php echo base_url(); ?>Packages" title="Pay Now" class="waves-effect waves-light dropdown-toggle">
		 <i class="ti-export" class="svg-icon" style="color:red;"></i>
	 </a>
	 </li><?php } ?>

				<!-- User Account-->
				<li class="dropdown user user-menu">

					<a href="javascript:void(0)" class="waves-effect waves-light dropdown-toggle" data-toggle="dropdown"
					   title="User">
						<img src="<?php echo "$dircheckPath"; ?>imgs/main_icons/svg-icon/user.svg"
							 class="rounded svg-icon" alt=""/>
					</a>
					<ul class="dropdown-menu animated flipInX">
						<!-- User image -->
						<li class="user-header bg-img"
							style="background-image: url(<?php echo "$dircheckPath"; ?>imgs/main_icons/user-info.jpg)"
							data-overlay="3">
							<div class="flexbox align-self-center">
								<img src="<?php echo "$dircheckPath"; ?>imgs/Currency_img\<?php if ($_SESSION['admin'] == '1') {
									echo '2705.png';
								} elseif ($_SESSION['package'] == '1') {
									echo 'yen.svg';
								} elseif ($_SESSION['package'] == '2') {
									echo 'eu.svg';
								} elseif ($_SESSION['package'] == '3') {
									echo 'pund.svg';
								} elseif ($_SESSION['package'] == '4') {
									echo 'usd.svg';
								} else {
									echo 'yen.svg';
								} ?>" class="float-left rounded-circle" alt="User Image">
								<h4 class="user-name align-self-center">
									<p><span><?php echo $_SESSION['Username']; ?></span><br>
										<small><?php echo $_SESSION['Email']; ?></small>
								</h4>
							</div>
						</li>
						<!-- Menu Body -->
						<li class="user-body">
							<a class="dropdown-item" href="<?php echo base_url(); ?>Setting"><i
										class="ion ion-person"></i><?php echo lang('edit_profile'); ?></a>
							<a class="dropdown-item" href="<?php echo base_url(); ?>Setting/general"><i
										class="fa fa-cog"></i><?php echo lang('general'); ?></a>
							<div class="dropdown-divider"></div>
							<a class="dropdown-item" href="<?php echo base_url(); ?>Setting/language"><i
										class="fa fa-language"></i> <?php echo lang('language'); ?></a>
							<div class='dropdown-divider'></div>
							<a class="dropdown-item" href="javascript:void(0)" onclick="mode()"><i
										class="fa fa-adjust"></i> <?php echo lang('mode'); ?></a>
							<div class='dropdown-divider'></div>
							<a class="dropdown-item" href="<?php echo base_url(); ?>help"><i
										class="fa fa-hand-paper-o"></i> <?php echo lang('help'); ?></a>
							<div class="dropdown-divider"></div>
							<a class="dropdown-item" href="<?php echo base_url(); ?>Packages"><i
										class="ti-export"></i> <?php echo lang('upgread'); ?></a>
							<div class="dropdown-divider"></div>
							<a class="dropdown-item" style="color:red;"
							   onclick="return confirm('<?php echo lang('are_logout'); ?>')"
							   href="<?php echo base_url(); ?>Account/logout"><i
										class="ion-log-out"></i> <?php echo lang('logout'); ?></a>

						</li>
					</ul>
				</li>


				<!-- Control Sidebar Toggle Button -->



			</ul>
		</div>
	</nav>
</header>

<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
	<!-- sidebar-->
	<section class="sidebar">
		<!-- sidebar menu-->
		<ul class="sidebar-menu" data-widget="tree">
			<li class="header"><?php echo lang('transactions'); ?></li>
			<?php if ($_SESSION['cash'] == '0') { ?>
				<li <?php
				if ($active == 'cash') {
					echo "class='active'";
				} ?>>
					<a <?php if ($_SESSION['title_h'] == '1') {
						echo "title='" . lang('cash_h') . "'";
					} ?> href="<?php echo base_url(); ?>Transaction/cash">
						<img src="<?php echo "$dircheckPath"; ?>imgs/main_icons/svg-icon/sidebar-menu/transactions.svg"
							 class="svg-icon" alt="">
						<span><?php echo lang('cash_pg'); ?></span>
					</a>
				</li>
			<?php } ?>
			<?php if ($_SESSION['chak'] == '0') { ?>
				<li <?php
				if ($active == 'chak') {
					echo "class='active'";
				} ?>>
					<a <?php if ($_SESSION['title_h'] == '1') {
						echo "title='" . lang('chak_h') . "'";
					} ?> href="<?php echo base_url(); ?>Transaction/Bonds">
						<img src="<?php echo "$dircheckPath"; ?>imgs/main_icons/svg-icon/sidebar-menu/invoice.svg"
							 class="svg-icon" alt="">
						<span><?php echo lang('chak_pg'); ?></span>
					</a>
				</li>
			<?php } ?>
			<?php if ($_SESSION['package'] == '2' || $_SESSION['package'] == '3' || $_SESSION['package'] == '4' || $_SESSION['admin'] == '1') {
				if ($_SESSION['transfar'] == '0') { ?>
					<li <?php
					if ($active == 'transfar') {
						echo "class='active'";
					} ?>>
						<a <?php if ($_SESSION['title_h'] == '1') {
							echo "title='" . lang('transfar_h') . "'";
						} ?> href="<?php echo base_url(); ?>Transaction/Transfar">
							<img src="<?php echo "$dircheckPath"; ?>imgs/main_icons/svg-icon/sidebar-menu/emails.svg"
								 class="svg-icon" alt="">
							<span><?php echo lang('transfar_pg'); ?></span>
						</a>
					</li>
				<?php } ?>
				<?php if ($_SESSION['cards'] == '0') { ?>
					<li <?php
					if ($active == 'cards') {
						echo "class='active'";
					} ?>>
						<a <?php if ($_SESSION['title_h'] == '1') {
							echo "title='" . lang('cards_h') . "'";
						} ?> href="<?php echo base_url(); ?>Transaction/Cards">
							<img src="<?php echo "$dircheckPath"; ?>imgs/main_icons/svg-icon/sidebar-menu/cards.svg"
								 class="svg-icon" alt="">
							<span><?php echo lang('cards_pg'); ?></span>
						</a>
					</li>
				<?php }} if($_SESSION['package'] == '3' || $_SESSION['package'] == '4' || $_SESSION['admin'] == '1'){ ?>
				<?php if ($_SESSION['invest'] == '0') { ?>
					<li>
						<a <?php if ($_SESSION['title_h'] == '1') {
							echo "title='" . lang('invest_h') . "'";
						} ?> href="<?php echo base_url(); ?>Transaction/Investment">
							<img src="<?php echo "$dircheckPath"; ?>imgs/main_icons/svg-icon/sidebar-menu/members.svg"
								 class="svg-icon" alt="">
							<span><?php echo lang('invest'); ?></span>
						</a>
					</li>
				<?php
			} }?>
			<?php
if ($_SESSION['package'] == '2' || $_SESSION['package'] == '3' || $_SESSION['package'] == '4' || $_SESSION['admin'] == '1' || $_SESSION['local_transfar'] == "1") {
 if ($_SESSION['transfar'] == '0') { ?>
				<li <?php
				if ($active == 'local_transfar') {
					echo "class='active'";
				} ?>>
					<a <?php if ($_SESSION['title_h'] == '1') {
						echo "title='" . lang('transfar_h') . "'";
					} ?> href="<?php echo base_url(); ?>Transaction/local_transfar">
						<img src="<?php echo "$dircheckPath"; ?>imgs/main_icons/svg-icon/widgets.svg"
							 class="svg-icon" alt="">
						<span><?php echo lang('local_transfar'); ?></span>
					</a>
				</li>
			<?php }} ?>
			<li class="header"><?php echo lang('accountss'); ?></li>
			<?php if ($_SESSION['Treasury'] == '0') { ?>
				<li <?php
				if ($active == 'wallet') {
					echo "class='active'";
				} ?>>
					<a <?php if ($_SESSION['title_h'] == '1') {
						echo "title='" . lang('wallet_h') . "'";
					} ?> href="<?php echo base_url(); ?>Wallet/">
						<img src="<?php echo "$dircheckPath"; ?>imgs/main_icons/svg-icon/basic.svg" class="svg-icon"
							 alt="">
						<span><?php echo lang('trsh'); ?></span>
					</a>
				</li>
			<?php } ?>
			<?php if ($_SESSION['buythings'] == '0') { ?>
				<li <?php
				if ($active == 'expenses') {
					echo "class='active'";
				} ?>>
					<a <?php if ($_SESSION['title_h'] == '1') {
						echo "title='" . lang('expenses_h') . "'";
					} ?> href="<?php echo base_url(); ?>Expenses">
						<img src="<?php echo "$dircheckPath"; ?>imgs/main_icons/svg-icon/sidebar-menu/forms1.svg"
							 class="svg-icon" alt="">
						<span><?php echo lang('buythings'); ?></span>
					</a>
				</li>
			<?php } ?>
			<?php if ($_SESSION['trash'] == '0') { ?>
				<li <?php
				if ($active == 'trash') {
					echo "class='active'";
				} ?>>
					<a <?php if ($_SESSION['title_h'] == '1') {
						echo "title='" . lang('trash_h') . "'";
					} ?> href="<?php echo base_url(); ?>Trash/">
						<img src="<?php echo "$dircheckPath"; ?>imgs/main_icons/svg-icon/sidebar-menu/miscellaneous.svg"
							 class="svg-icon" alt="">
						<span><?php echo lang('trash'); ?></span>
					</a>
				</li>
			<?php } ?>
			<?php if ($_SESSION['admin'] == '1' || $_SESSION['type'] == 'boss' || $_SESSION['type'] == 'admin') {
				if ($_SESSION['package'] == '2' || $_SESSION['package'] == '3' || $_SESSION['package'] == '4' || $_SESSION['admin'] == '1') { ?>
					<li>
						<a href="<?php echo base_url(); ?><?php if ($_SESSION['admin'] == '1') {
							echo 'Dashboard/panel';
						} elseif ($_SESSION['type'] == 'boss' || $_SESSION['type'] == 'admin') {
							if ($_SESSION['package'] == '2' || $_SESSION['package'] == '3' || $_SESSION['package'] == '4' || $_SESSION['admin'] == '1') {
								echo 'mycontrol/index';
							}
						} ?>">
							<img src="<?php echo "$dircheckPath"; ?>imgs/main_icons/svg-icon/sidebar-menu/extensions.svg"
								 class="svg-icon" alt="">
							<span><?php echo lang('control_panel'); ?></span>
						</a>
					</li>
				<?php }
			} ?>
			<li class="treeview">
				<a href="<?php echo base_url(); ?>Reports">
					<img src="<?php echo "$dircheckPath"; ?>imgs/main_icons/svg-icon/sidebar-menu/reports.svg"
						 class="svg-icon" alt="">
					<span><?php echo lang('Archive'); ?></span>
					<span class="pull-right-container">
              <i class="fa fa-angle-right pull-right"></i>
            </span>
				</a>
				<ul class="treeview-menu">
					<li <?php if ($active == 'reports') {
						echo "class='active'";
					} ?>><a href="<?php echo base_url(); ?>Reports"><i
									class="ti-more"></i><?php echo lang('Archive'); ?></a></li>
					<?php if ($_SESSION['chak'] == '0') { ?>
						<li <?php if ($active == 'bondchakarc') {
							echo "class='active'";
						} ?>><a href="<?php echo base_url(); ?>bonds_reports"><i
										class="ti-more"></i><?php echo lang('chak_archive'); ?></a></li>
					<?php }
					if ($_SESSION['package'] == '2' || $_SESSION['package'] == '3' || $_SESSION['package'] == '4' || $_SESSION['admin'] == '1') {
						if ($_SESSION['cards'] == '0') { ?>
							<li <?php if ($active == 'cardarc') {
								echo "class='active'";
							} ?>><a href="<?php echo base_url(); ?>card_reports"><i
											class="ti-more"></i><?php echo lang('cards_archive'); ?></a></li>
						<?php }
						if ($_SESSION['transfar'] == '0') { ?>
							<li <?php if ($active == 'chakarc') {
								echo "class='active'";
							} ?>><a href="<?php echo base_url(); ?>transfar_reports"><i
											class="ti-more"></i><?php echo lang('transfar_archive'); ?></a></li>
						<?php }
					}
					if ($_SESSION['admin'] == '1' || $_SESSION['type'] == 'boss' || $_SESSION['type'] == 'admin') { ?>
						<li <?php if ($active == 'embarchive') {
							echo "class='active'";
						} ?>><a href="<?php echo base_url(); ?>embloyees_reports"><i
										class="ti-more"></i><?php echo lang('embarc'); ?></a></li>
					<?php } ?>
					<li <?php if ($active == 'Detailed_reports') {
						echo "class='active'";
					} ?>><a href="<?php echo base_url(); ?>detailed_reports"><i
									class="ti-more"></i><?php echo lang('detailed_reports'); ?></a></li>
					<li <?php if ($active == 'bill') {
						echo "class='active'";
					} ?>><a href="<?php echo base_url(); ?>bill_reports"><i
									class="ti-more"></i><?php echo lang('bill_arch'); ?></a></li>

					<li <?php if ($active == 'finel_reports') {
						echo "class='active'";
					} ?>><a href="<?php echo base_url(); ?>finel_reports"><i
									class="ti-more"></i><?php echo lang('finel_reports'); ?></a></li>
				</ul>
			</li>
			<li class="treeview">
				<a href="<?php echo base_url(); ?>settings">
					<img src="<?php echo "$dircheckPath"; ?>imgs/main_icons/svg-icon/sidebar-menu/advancedui.svg"
						 class="svg-icon" alt="">
					<span><?php echo lang('settings'); ?></span>
					<span class="pull-right-container">
              <i class="fa fa-angle-right pull-right"></i>
            </span>
				</a>
				<ul class="treeview-menu">
					<li <?php
					if ($tc == 'general') {
						echo "class='active'";
					}

					?>><a href="<?php echo base_url(); ?>Setting/general"><i
									class="ti-more"></i><?php echo lang('general'); ?></a></li>
					<li <?php
					if ($tc == 'edit_profile') {
						echo "class='active'";
					}

					?>><a href="<?php echo base_url(); ?>Setting"><i
									class="ti-more"></i><?php echo lang('edit_profile'); ?></a></li>
					<li <?php
					if ($tc == 'mybank' || $tc == 'mybanknew') {
						echo "class='active'";
					}

					?>><a href="<?php echo base_url(); ?>Setting/mybank"><i
									class="ti-more"></i><?php echo lang('Banks_information'); ?></a></li>
					<li <?php
					if ($tc == 'bank') {
						echo "class='active'";
					}

					?>><a href="<?php echo base_url(); ?>Setting/bank"><i
									class="ti-more"></i><?php echo lang('Banks_information_cos'); ?></a></li>
					<li <?php
					if ($tc == 'language') {
						echo "class='active'";
					}

					?>><a href="<?php echo base_url(); ?>Setting/language"><i
									class="ti-more"></i><?php echo lang('language'); ?></a></li>
					<li <?php
					if ($tc == 'mode') {
						echo "class='active'";
					}

					?>><a href="<?php echo base_url(); ?>Setting/mode"><i
									class="ti-more"></i><?php echo lang('mode'); ?></a></li>
				</ul>
			</li>

			<li class="header"><?php echo lang('logout'); ?></li>
			<li>
				<a onclick="return confirm('<?php echo lang('confirm_logout'); ?>')" accesskey="l"
				   href="<?php echo base_url(); ?>Account/logout">
					<img src="<?php echo "$dircheckPath"; ?>imgs/main_icons/svg-icon/sidebar-menu/logout.svg"
						 class="svg-icon" alt="">
					<span><?php echo lang('logout'); ?></span>
				</a>
			</li>

		</ul>
	</section>
</aside>
<script>
	const ribbons = document.querySelectorAll(".ribbon");
	ribbons.forEach((ribbon) => {
		ribbon.addEventListener('click', (e) => {
			let target = e.target;
			while (target !== ribbon) {
				target = target.parentNode;
			}
			const types = ['', 'slant-up', 'slant-down', 'up', 'down', 'check'];
			const type = types[Math.floor(Math.random() * types.length)];
			target.className = `ribbon ${type}`;
		});
	});
</script>
