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

/* modules/contrib/commerce_funds/templates/commerce-funds-deposit-completion-message.html.twig */
class __TwigTemplate_b838b8a623485588e9d3f325c3ddf5b006eaece1d40f97d63e4e21a1d5028db4 extends \Twig\Template
{
    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = [
        ];
        $this->sandbox = $this->env->getExtension('\Twig\Extension\SandboxExtension');
        $tags = ["if" => 22];
        $filters = ["t" => 16, "escape" => 25];
        $functions = [];

        try {
            $this->sandbox->checkSecurity(
                ['if'],
                ['t', 'escape'],
                []
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
        // line 15
        echo "<div class=\"checkout-complete\">
  ";
        // line 16
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->renderVar(t("@amount @currency have been added to your account balance.", ["@amount" =>         // line 17
($context["amount"] ?? null), "@currency" =>         // line 18
($context["currency_code"] ?? null)]));
        echo "
  ";
        // line 19
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->renderVar(t("Your order number is @number.", ["@number" => $this->getAttribute(($context["order_entity"] ?? null), "getOrderNumber", [])]));
        echo " <br>
  ";
        // line 20
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->renderVar(t("You can view your order on your account page when logged in."));
        echo " <br>

  ";
        // line 22
        if (($context["payment_instructions"] ?? null)) {
            // line 23
            echo "    <div class=\"checkout-complete__payment-instructions\">
      <h2>";
            // line 24
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->renderVar(t("Payment instructions"));
            echo "</h2>
      ";
            // line 25
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["payment_instructions"] ?? null)), "html", null, true);
            echo "
    </div>
  ";
        }
        // line 28
        echo "</div>
";
    }

    public function getTemplateName()
    {
        return "modules/contrib/commerce_funds/templates/commerce-funds-deposit-completion-message.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  88 => 28,  82 => 25,  78 => 24,  75 => 23,  73 => 22,  68 => 20,  64 => 19,  60 => 18,  59 => 17,  58 => 16,  55 => 15,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Source("", "modules/contrib/commerce_funds/templates/commerce-funds-deposit-completion-message.html.twig", "C:\\xampp\\htdocs\\commerce\\modules\\contrib\\commerce_funds\\templates\\commerce-funds-deposit-completion-message.html.twig");
    }
}
