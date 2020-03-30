<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;

/* modules/contrib/commerce_purchase_order/templates/commerce-order-receipt--purchase-order-gateway.html.twig */
class __TwigTemplate_9c6025ae58de852363dd55a307fdef598bb6425d4180638e56c5850f766fe697 extends \Twig\Template
{
    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = [
            'order_items' => [$this, 'block_order_items'],
            'shipping_information' => [$this, 'block_shipping_information'],
            'billing_information' => [$this, 'block_billing_information'],
            'payment_method' => [$this, 'block_payment_method'],
            'additional_information' => [$this, 'block_additional_information'],
        ];
        $this->sandbox = $this->env->getExtension('\Twig\Extension\SandboxExtension');
        $tags = ["block" => 51, "if" => 72, "for" => 124];
        $filters = ["escape" => 34, "t" => 42, "commerce_price_format" => 120, "number_format" => 57];
        $functions = ["url" => 34];

        try {
            $this->sandbox->checkSecurity(
                ['block', 'if', 'for'],
                ['escape', 't', 'commerce_price_format', 'number_format'],
                ['url']
            );
        } catch (SecurityError $e) {
            $e->setSourceContext($this->getSourceContext());

            if ($e instanceof SecurityNotAllowedTagError && isset($tags[$e->getTagName()])) {
                $e->setTemplateLine($tags[$e->getTagName()]);
            } elseif ($e instanceof SecurityNotAllowedFilterError && isset($filters[$e->getFilterName()])) {
                $e->setTemplateLine($filters[$e->getFilterName()]);
            } elseif ($e instanceof SecurityNotAllowedFunctionError && isset($functions[$e->getFunctionName()])) {
                $e->setTemplateLine($functions[$e->getFunctionName()]);
            }

            throw $e;
        }

    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        // line 26
        echo "<table style=\"margin: 15px auto 0 auto; max-width: 768px; font-family: arial,sans-serif\">
  <tbody>
  <tr>
    <td>
      <table style=\"margin-left: auto; margin-right: auto; max-width: 768px; text-align: center;\">
        <tbody>
        <tr>
          <td>
            <a href=\"";
        // line 34
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->renderVar($this->env->getExtension('Drupal\Core\Template\TwigExtension')->getUrl("<front>"));
        echo "\" style=\"color: #0e69be; text-decoration: none; font-weight: bold; margin-top: 15px;\">";
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute($this->getAttribute(($context["order_entity"] ?? null), "getStore", []), "label", [])), "html", null, true);
        echo "</a>
          </td>
        </tr>
        </tbody>
      </table>
      <table style=\"text-align: center; min-width: 450px; margin: 5px auto 0 auto; border: 1px solid #cccccc; border-radius: 5px; padding: 40px 30px 30px 30px;\">
        <tbody>
        <tr>
          <td style=\"font-size: 30px; padding-bottom: 30px\">";
        // line 42
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->renderVar(t("Order Confirmation"));
        echo "</td>
        </tr>
        <tr>
          <td style=\"font-weight: bold; padding-top:15px; padding-bottom: 15px; text-align: left; border-top: 1px solid #cccccc; border-bottom: 1px solid #cccccc\">
            ";
        // line 46
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->renderVar(t("Order #@number details:", ["@number" => $this->getAttribute(($context["order_entity"] ?? null), "getOrderNumber", [])]));
        echo "
          </td>
        </tr>
        <tr>
          <td>
            ";
        // line 51
        $this->displayBlock('order_items', $context, $blocks);
        // line 68
        echo "          </td>
        </tr>
        <tr>
          <td>
            ";
        // line 72
        if ((($context["billing_information"] ?? null) || ($context["shipping_information"] ?? null))) {
            // line 73
            echo "              <table style=\"width: 100%; padding-top:15px; padding-bottom: 15px; text-align: left; border-top: 1px solid #cccccc; border-bottom: 1px solid #cccccc\">
                <tbody>
                <tr>
                  ";
            // line 76
            if (($context["shipping_information"] ?? null)) {
                // line 77
                echo "                    <td style=\"padding-top: 5px; font-weight: bold;\">";
                echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->renderVar(t("Shipping Information"));
                echo "</td>
                  ";
            }
            // line 79
            echo "                  ";
            if (($context["billing_information"] ?? null)) {
                // line 80
                echo "                    <td style=\"padding-top: 5px; font-weight: bold;\">";
                echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->renderVar(t("Billing Information"));
                echo "</td>
                  ";
            }
            // line 82
            echo "                </tr>
                <tr>
                  ";
            // line 84
            if (($context["shipping_information"] ?? null)) {
                // line 85
                echo "                    <td>
                      ";
                // line 86
                $this->displayBlock('shipping_information', $context, $blocks);
                // line 89
                echo "                    </td>
                  ";
            }
            // line 91
            echo "                  ";
            if (($context["billing_information"] ?? null)) {
                // line 92
                echo "                    <td>
                      ";
                // line 93
                $this->displayBlock('billing_information', $context, $blocks);
                // line 96
                echo "                    </td>
                  ";
            }
            // line 98
            echo "                </tr>
                ";
            // line 99
            if (($context["payment_method"] ?? null)) {
                // line 100
                echo "                  <tr>
                    <td style=\"font-weight: bold; margin-top: 10px;\">";
                // line 101
                echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->renderVar(t("Payment Method"));
                echo "</td>
                  </tr>
                  <tr>
                    <td>
                      ";
                // line 105
                $this->displayBlock('payment_method', $context, $blocks);
                // line 109
                echo "                    </td>
                  </tr>
                ";
            }
            // line 112
            echo "                </tbody>
              </table>
            ";
        }
        // line 115
        echo "          </td>
        </tr>
        <tr>
          <td>
            <p style=\"margin-bottom: 0;\">
              ";
        // line 120
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->renderVar(t("Subtotal: @subtotal", ["@subtotal" => $this->env->getExtension('Drupal\commerce_price\TwigExtension\PriceTwigExtension')->formatPrice($this->sandbox->ensureToStringAllowed($this->getAttribute(($context["totals"] ?? null), "subtotal", [])))]));
        echo "
            </p>
          </td>
        </tr>
        ";
        // line 124
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute(($context["totals"] ?? null), "adjustments", []));
        foreach ($context['_seq'] as $context["_key"] => $context["adjustment"]) {
            // line 125
            echo "          <tr>
            <td>
              <p style=\"margin-bottom: 0;\">
                ";
            // line 128
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute($context["adjustment"], "label", [])), "html", null, true);
            echo ": ";
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->env->getExtension('Drupal\commerce_price\TwigExtension\PriceTwigExtension')->formatPrice($this->sandbox->ensureToStringAllowed($this->getAttribute($context["adjustment"], "total", []))), "html", null, true);
            echo "
              </p>
            </td>
          </tr>
        ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['adjustment'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 133
        echo "        <tr>
          <td>
            <p style=\"font-size: 24px; padding-top: 15px; padding-bottom: 5px;\">
              ";
        // line 136
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->renderVar(t("Order Total: @total", ["@total" => $this->env->getExtension('Drupal\commerce_price\TwigExtension\PriceTwigExtension')->formatPrice($this->sandbox->ensureToStringAllowed($this->getAttribute(($context["order_entity"] ?? null), "getTotalPrice", [])))]));
        echo "
            </p>
          </td>
        </tr>
        <tr>
          <td>
            ";
        // line 142
        $this->displayBlock('additional_information', $context, $blocks);
        // line 145
        echo "          </td>
        </tr>
        </tbody>
      </table>
    </td>
  </tr>
  </tbody>
</table>
";
    }

    // line 51
    public function block_order_items($context, array $blocks = [])
    {
        // line 52
        echo "              <table style=\"padding-top: 15px; padding-bottom:15px; width: 100%\">
                <tbody style=\"text-align: left;\">
                ";
        // line 54
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute(($context["order_entity"] ?? null), "getItems", []));
        foreach ($context['_seq'] as $context["_key"] => $context["order_item"]) {
            // line 55
            echo "                  <tr>
                    <td>
                      ";
            // line 57
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, twig_number_format_filter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute($context["order_item"], "getQuantity", []))), "html", null, true);
            echo " x
                    </td>
                    <td>
                      <span>";
            // line 60
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute($context["order_item"], "label", [])), "html", null, true);
            echo "</span>
                      <span style=\"float: right;\">";
            // line 61
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->env->getExtension('Drupal\commerce_price\TwigExtension\PriceTwigExtension')->formatPrice($this->sandbox->ensureToStringAllowed($this->getAttribute($context["order_item"], "getTotalPrice", []))), "html", null, true);
            echo "</span>
                    </td>
                  </tr>
                ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['order_item'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 65
        echo "                </tbody>
              </table>
            ";
    }

    // line 86
    public function block_shipping_information($context, array $blocks = [])
    {
        // line 87
        echo "                        ";
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["shipping_information"] ?? null)), "html", null, true);
        echo "
                      ";
    }

    // line 93
    public function block_billing_information($context, array $blocks = [])
    {
        // line 94
        echo "                        ";
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["billing_information"] ?? null)), "html", null, true);
        echo "
                      ";
    }

    // line 105
    public function block_payment_method($context, array $blocks = [])
    {
        // line 106
        echo "                        ";
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["payment_method"] ?? null)), "html", null, true);
        echo "
                        ";
        // line 107
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["payment_instructions"] ?? null)), "html", null, true);
        echo "
                      ";
    }

    // line 142
    public function block_additional_information($context, array $blocks = [])
    {
        // line 143
        echo "              ";
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->renderVar(t("Thank you for your order!"));
        echo "
            ";
    }

    public function getTemplateName()
    {
        return "modules/contrib/commerce_purchase_order/templates/commerce-order-receipt--purchase-order-gateway.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  326 => 143,  323 => 142,  317 => 107,  312 => 106,  309 => 105,  302 => 94,  299 => 93,  292 => 87,  289 => 86,  283 => 65,  273 => 61,  269 => 60,  263 => 57,  259 => 55,  255 => 54,  251 => 52,  248 => 51,  236 => 145,  234 => 142,  225 => 136,  220 => 133,  207 => 128,  202 => 125,  198 => 124,  191 => 120,  184 => 115,  179 => 112,  174 => 109,  172 => 105,  165 => 101,  162 => 100,  160 => 99,  157 => 98,  153 => 96,  151 => 93,  148 => 92,  145 => 91,  141 => 89,  139 => 86,  136 => 85,  134 => 84,  130 => 82,  124 => 80,  121 => 79,  115 => 77,  113 => 76,  108 => 73,  106 => 72,  100 => 68,  98 => 51,  90 => 46,  83 => 42,  70 => 34,  60 => 26,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Source("", "modules/contrib/commerce_purchase_order/templates/commerce-order-receipt--purchase-order-gateway.html.twig", "C:\\xampp\\htdocs\\commerce\\modules\\contrib\\commerce_purchase_order\\templates\\commerce-order-receipt--purchase-order-gateway.html.twig");
    }
}
