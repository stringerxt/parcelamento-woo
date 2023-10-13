<?php
/**
 * Plugin Name: Meu Parcelamento PIX/BOLETO/CARTÃO
 * Description: Plugin para exibir opções de pagamento personalizadas.
 * Version: 1.9
 * Author: Luis Antonio
 */

// Função para exibir as opções de pagamento personalizadas
function mpg_meu_shortcode_woocommerce() {
    global $product;

    // Verifica se existe um produto
    if ( ! $product ) {
        return '';
    }

    // Obtém as configurações de tamanho da fonte para preços e cor da fonte
    $tamanho_fonte_precos = get_option('mpg_tamanho_fonte_precos', '16px');
    $cor_fonte = get_option('mpg_cor_fonte', '#000000');
    $parcelas_sem_juros = get_option('mpg_parcelas_semjuros', 10);
    $desconto_pix = get_option('mpg_desconto_pix', 5);
    $desconto_boleto = get_option('mpg_desconto_boleto', 6);

    $preco_regular = $product->get_price();
    $preco_desconto = $product->get_sale_price();

    // Verifica se há desconto no produto
    if ( $preco_desconto < $preco_regular ) {
        $preco_boleto = $preco_regular * (100 - $desconto_boleto) / 100;
        $preco_desconto_avista = $preco_regular * (100 - $desconto_pix) / 100;
    } else {
        // Se não houver desconto, usa o preço regular para boleto e pagamento à vista
        $preco_boleto = $preco_regular;
        $preco_desconto_avista = $preco_regular;
    }

    $layout = intval(get_option('mpg_layout'));

    ob_start();
    ?>
    <style>
        .informacoes-produto {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            margin-bottom: 20px;
        }

        .opcao-pagamento {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .opcao-pagamento img {
            margin-right: 10px;
            width: 25px;
            height: 25px;
        }

        .opcao-pagamento span.preco {
            font-size: <?php echo $tamanho_fonte_precos; ?>;
            color: <?php echo $cor_fonte; ?>;
            font-weight: bold;
        }

        .opcao-pagamento span.parcelas {
            font-size: <?php echo $tamanho_fonte_precos; ?>;
            color: <?php echo $cor_fonte; ?>;
        }

        .ver-parcelas {
            text-decoration: none;
            color: #007bff;
            cursor: pointer;
        }

        .parcelas-info {
            display: none;
            margin-top: 10px;
            padding-left: 30px;
        }

        .parcelas-info span {
            display: block;
        }
    </style>

     <div class="informacoes-produto">
		<?php if ($layout === 1) { ?>						 
    <div class="opcao-pagamento">
        <img src="<?php echo plugin_dir_url(__FILE__) . 'images/cartao.png'; ?>" alt="Ícone 4">
        <span class="parcelas"><?php echo $parcelas_sem_juros; ?>x de</span>&nbsp;
        <span class="preco">R$ <?php echo number_format( $preco_regular / $parcelas_sem_juros, 2, ',', '.' ); ?>&nbsp;</span>
        <span class="parcelas">sem juros no Cartão</span>
    </div>

      <div class="opcao-pagamento">
                <img src="<?php echo plugin_dir_url(__FILE__) . 'images/boleto.png'; ?>" alt="Ícone 2">
                <span class="preco">R$ <?php echo number_format($preco_regular - ($preco_regular * ($desconto_boleto / 100)), 2, ',', '.'); ?>&nbsp;</span>
                <span class "parcelas">à vista no Boleto (Desconto de <?php echo $desconto_boleto; ?>%)</span>
            </div>

		<div class="opcao-pagamento">
                <img src="<?php echo plugin_dir_url(__FILE__) . 'images/pix.png'; ?>" alt="Ícone 1">
                <span class="preco">R$ <?php echo number_format($preco_regular - ($preco_regular * ($desconto_pix / 100)), 2, ',', '.'); ?>&nbsp;</span>
                <span class="parcelas">à vista no Pix (Desconto de <?php echo $desconto_pix; ?>%)</span>
            </div>

    <p>
        <u><a href="#" class="ver-parcelas">Ver parcelas</a></u>
    </p>

    <div class="parcelas-info">
        <span>1x sem juros de R$ <?php echo number_format($preco_regular, 2, ',', '.'); ?> no Cartão de crédito</span>
        <?php
        for ($i = 2; $i <= $parcelas_sem_juros; $i++) {
            $valor_parcela = $preco_regular / $i;
            echo "<span>{$i}x sem juros de R$ " . number_format($valor_parcela, 2, ',', '.') . " no Cartão de crédito</span>";
        }
        ?>
		 <?php } elseif ($layout === 2) { ?>
		  <div class="opcao-pagamento">
                <img src="<?php echo plugin_dir_url(__FILE__) . 'images/pix.png'; ?>" alt="Ícone 1">
                <span class="preco">R$ <?php echo number_format($preco_regular - ($preco_regular * ($desconto_pix / 100)), 2, ',', '.'); ?>&nbsp;</span>
                <span class "parcelas">à vista no Pix (Desconto de <?php echo $desconto_pix; ?>%)</span>
            </div>

            <div class="opcao-pagamento">
                <img src="<?php echo plugin_dir_url(__FILE__) . 'images/boleto.png'; ?>" alt="Ícone 2">
                <span class="preco">R$ <?php echo number_format($preco_regular - ($preco_regular * ($desconto_boleto / 100)), 2, ',', '.'); ?>&nbsp;</span>
                <span class "parcelas">à vista no Boleto (Desconto de <?php echo $desconto_boleto; ?>%)</span>
            </div>

           <div class="opcao-pagamento">
        <img src="<?php echo plugin_dir_url(__FILE__) . 'images/cartao.png'; ?>" alt="Ícone 4">
        <span class="parcelas"><?php echo $parcelas_sem_juros; ?>x de</span>&nbsp;
        <span class="preco">R$ <?php echo number_format( $preco_regular / $parcelas_sem_juros, 2, ',', '.' ); ?>&nbsp;</span>
        <span class="parcelas">sem juros no Cartão</span>
    </div>

            <a href="#" class="ver-parcelas">Ver Parcelas</a>
			    <div class="parcelas-info">
        <span>1x sem juros de R$ <?php echo number_format($preco_regular, 2, ',', '.'); ?> no Cartão de crédito</span>
        <?php
        for ($i = 2; $i <= $parcelas_sem_juros; $i++) {
            $valor_parcela = $preco_regular / $i;
            echo "<span>{$i}x sem juros de R$ " . number_format($valor_parcela, 2, ',', '.') . " no Cartão de crédito</span>";
        }
        ?>
                <!-- Aqui você pode exibir informações sobre as parcelas -->
            </div>
        <?php } ?>
    </div>
</div>
   <script>
        document.addEventListener('DOMContentLoaded', function() {
            var verParcelas = document.querySelector('.ver-parcelas');
            var parcelasInfo = document.querySelector('.parcelas-info');

            verParcelas.addEventListener('click', function(e) {
                e.preventDefault();
                parcelasInfo.style.display = parcelasInfo.style.display === 'none' ? 'block' : 'none';
            });
        });
    </script>

    <?php
    return ob_get_clean();
}
add_shortcode('meu_shortcode', 'mpg_meu_shortcode_woocommerce');

// Função para registrar as configurações do plugin
function mpg_meu_plugin_pagamento_register_settings() {
    add_option('mpg_tamanho_fonte_precos', '16px');
    add_option('mpg_cor_fonte', '#000000');
    add_option('mpg_parcelas_semjuros', 10);
    add_option('mpg_desconto_pix', 5);
    add_option('mpg_desconto_boleto', 6);
    register_setting('meu-plugin-pagamento-settings-group', 'mpg_tamanho_fonte_precos');
    register_setting('meu-plugin-pagamento-settings-group', 'mpg_cor_fonte');
    register_setting('meu-plugin-pagamento-settings-group', 'mpg_parcelas_semjuros');
    register_setting('meu-plugin-pagamento-settings-group', 'mpg_desconto_pix');
    register_setting('meu-plugin-pagamento-settings-group', 'mpg_desconto_boleto');
	add_option('mpg_layout', '1');
	register_setting('meu-plugin-pagamento-settings-group', 'mpg_layout');
}

// Função para criar a página de configurações do plugin
function mpg_meu_plugin_pagamento_create_menu() {
    add_menu_page('Configurações do Meu Plugin de Pagamento', 'Plugin Pagamento', 'manage_options', 'meu-plugin-pagamento-settings', 'mpg_meu_plugin_pagamento_settings_page', 'dashicons-tickets', 30);
}

// Função para renderizar a página de configurações do plugin
function mpg_meu_plugin_pagamento_settings_page() {
    ?>
    <div class="wrap">
        <h1>Configurações do Meu Plugin de Pagamento</h1>

        <p>Use o shortcode <code>[meu_shortcode]</code> para exibir as opções de pagamento personalizadas em qualquer lugar do seu site.</p>

        <h2>Instruções de Uso</h2>

        <ol>
            <li>Insira o shortcode <code>[meu_shortcode]</code> no local onde deseja exibir as opções de pagamento.</li>
            <li>Personalize as configurações abaixo de acordo com suas preferências.</li>
        </ol>

        <form method="post" action="options.php">
            <?php settings_fields('meu-plugin-pagamento-settings-group'); ?>
            <?php do_settings_sections('meu-plugin-pagamento-settings-group'); ?>

            <table class="form-table">
				<tr valign="top">
					<th scope="row">Layout</th>
					<td>
						<select name="mpg_layout">
							<option value="1" <?php selected( get_option('mpg_layout'), '1' ); ?>>Layout 1</option>
							<option value="2" <?php selected( get_option('mpg_layout'), '2' ); ?>>Layout 2</option>
						</select>
					</td>
				</tr>
                <tr valign="top">
                    <th scope="row">Tamanho da Fonte para Preços</th>
                    <td>
                        <input type="text" name="mpg_tamanho_fonte_precos" value="<?php echo esc_attr(get_option('mpg_tamanho_fonte_precos')); ?>" />
                    </td>
                </tr>

                <tr valign="top">
                    <th scope="row">Cor da Fonte</th>
                    <td>
                        <input type="color" name="mpg_cor_fonte" value="<?php echo esc_attr(get_option('mpg_cor_fonte')); ?>" />
                    </td>
                </tr>

                <tr valign="top">
                    <th scope="row">Parcelas sem Juros</th>
                    <td>
                        <input type="number" name="mpg_parcelas_semjuros" min="1" value="<?php echo esc_attr(get_option('mpg_parcelas_semjuros')); ?>" />
                    </td>
                </tr>

                <tr valign="top">
                    <th scope="row">Desconto no Pagamento com Pix (%)</th>
                    <td>
                        <input type="number" name="mpg_desconto_pix" min="0" max="100" value="<?php echo esc_attr(get_option('mpg_desconto_pix')); ?>" />
                    </td>
                </tr>

                <tr valign="top">
                    <th scope="row">Desconto no Pagamento com Boleto (%)</th>
                    <td>
                        <input type="number" name="mpg_desconto_boleto" min="0" max="100" value="<?php echo esc_attr(get_option('mpg_desconto_boleto')); ?>" />
                    </td>
                </tr>
				
            </table>

            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

// Registro das configurações e criação do menu
add_action('admin_init', 'mpg_meu_plugin_pagamento_register_settings');
add_action('admin_menu', 'mpg_meu_plugin_pagamento_create_menu');
