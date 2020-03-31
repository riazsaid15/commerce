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

/* modules/contrib/commerce_funds/templates/user-operations.html.twig */
class __TwigTemplate_68a0a311d28a209077db23d8c9b06f0a13abbb6e918564a2ba04d2681689f9d4 extends \Twig\Template
{
    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = [
        ];
        $this->sandbox = $this->env->getExtension('\Twig\Extension\SandboxExtension');
        $tags = ["if" => 16];
        $filters = ["t" => 17];
        $functions = ["path" => 17];

        try {
            $this->sandbox->checkSecurity(
                ['if'],
                ['t'],
                ['path']
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
        echo "<ul>
  ";
        // line 16
        if ($this->getAttribute(($context["user"] ?? null), "hasPermission", [0 => "deposit funds"], "method")) {
            // line 17
            echo "      <li><a href=\"";
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->renderVar($this->env->getExtension('Drupal\Core\Template\TwigExtension')->getPath("commerce_funds.deposit"));
            echo "\" class=\"operation-link\">";
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->renderVar(t("Deposit funds"));
            echo "</a></li>
  ";
        }
        // line 19
        echo "  ";
        if ($this->getAttribute(($context["user"] ?? null), "hasPermission", [0 => "transfer funds"], "method")) {
            // line 20
            echo "    <li><a href=\"";
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->renderVar($this->env->getExtension('Drupal\Core\Template\TwigExtension')->getPath("commerce_funds.transfer"));
            echo "\" class=\"operation-link\">";
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->renderVar(t("Transfer funds"));
            echo "</a></li>
  ";
        }
        // line 22
        echo "  ";
        if ($this->getAttribute(($context["user"] ?? null), "hasPermission", [0 => "create escrow payment"], "method")) {
            // line 23
            echo "    <li><a href=\"";
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->renderVar($this->env->getExtension('Drupal\Core\Template\TwigExtension')->getPath("commerce_funds.escrow"));
            echo "\" class=\"operation-link\">";
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->renderVar(t("Create escrow payment"));
            echo "</a></li>
  ";
        }
        // line 25
        echo "  ";
        if (($this->getAttribute(($context["user"] ?? null), "hasPermission", [0 => "withdraw funds"], "method") && ($context["withdrawal_methods"] ?? null))) {
            // line 26
            echo "    <li><a href=\"";
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->renderVar($this->env->getExtension('Drupal\Core\Template\TwigExtension')->getPath("commerce_funds.withdraw"));
            echo "\" class=\"operation-link\">";
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->renderVar(t("Withdraw funds"));
            echo "</a></li>
  ";
        }
        // line 28
        echo "  ";
        if ($this->getAttribute(($context["user"] ?? null), "hasPermission", [0 => "create escrow payment"], "method")) {
            // line 29
            echo "    <li><a href=\"";
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->renderVar($this->env->getExtension('Drupal\Core\Template\TwigExtension')->getPath("view.commerce_funds_user_transactions.incoming_escrow_payments"));
            echo "\" class=\"operation-link\">";
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->renderVar(t("Manage escrow payments"));
            echo "</a></li>
  ";
        }
        // line 31
        echo "  ";
        if (($this->getAttribute(($context["user"] ?? null), "hasPermission", [0 => "withdraw funds"], "method") && ($context["withdrawal_methods"] ?? null))) {
            // line 32
            echo "    <li><a href=\"";
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->renderVar($this->env->getExtension('Drupal\Core\Template\TwigExtension')->getPath("view.commerce_funds_withdrawal_requests.pending_requests"));
            echo "\" class=\"operation-link\">";
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->renderVar(t("View withdrawal requests"));
            echo "</a></li>
  ";
        }
        // line 34
        echo "  ";
        if ($this->getAttribute(($context["user"] ?? null), "hasPermission", [0 => "view own transactions"], "method")) {
            // line 35
            echo "    <li><a href=\"";
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->renderVar($this->env->getExtension('Drupal\Core\Template\TwigExtension')->getPath("view.commerce_funds_user_transactions.issued_transactions"));
            echo "\" class=\"operation-link\">";
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->renderVar(t("View all transactions"));
            echo "</a></li>
  ";
        }
        // line 37
        echo "  ";
        if (($this->getAttribute(($context["user"] ?? null), "hasPermission", [0 => "convert currencies"], "method") && ($context["exchange_rates"] ?? null))) {
            // line 38
            echo "    <li><a href=\"";
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->renderVar($this->env->getExtension('Drupal\Core\Template\TwigExtension')->getPath("commerce_funds.convert_currencies"));
            echo "\" class=\"operation-link\">";
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->renderVar(t("Convert currencies"));
            echo "</a></li>
  ";
        }
        // line 40
        echo "</ul>
";
    }

    public function getTemplateName()
    {
        return "modules/contrib/commerce_funds/templates/user-operations.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  145 => 40,  137 => 38,  134 => 37,  126 => 35,  123 => 34,  115 => 32,  112 => 31,  104 => 29,  101 => 28,  93 => 26,  90 => 25,  82 => 23,  79 => 22,  71 => 20,  68 => 19,  60 => 17,  58 => 16,  55 => 15,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Source("", "modules/contrib/commerce_funds/templates/user-operations.html.twig", "C:\\xampp\\htdocs\\commerce\\modules\\contrib\\commerce_funds\\templates\\user-operations.html.twig");
    }
}
