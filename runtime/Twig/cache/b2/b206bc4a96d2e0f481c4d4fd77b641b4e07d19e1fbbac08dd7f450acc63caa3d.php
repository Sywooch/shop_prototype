<?php

/* content.twig */
class __TwigTemplate_56de9b01b1191049d5dd0ed9fb87bf7a30dbb92347c247a8644d38e58747b7ca extends yii\twig\Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "
<ol>
";
        // line 3
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["productsList"]) ? $context["productsList"] : null));
        foreach ($context['_seq'] as $context["_key"] => $context["product"]) {
            // line 4
            echo "    <li>
        ";
            // line 5
            echo twig_escape_filter($this->env, $this->getAttribute($context["product"], "name", array()), "html", null, true);
            echo " &mdash; ";
            echo twig_escape_filter($this->env, $this->getAttribute($context["product"], "price", array()), "html", null, true);
            echo "<br>
        ";
            // line 6
            echo twig_escape_filter($this->env, $this->getAttribute($context["product"], "description", array()), "html", null, true);
            echo "
    </li>
";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['product'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 9
        echo "</ol>
";
    }

    public function getTemplateName()
    {
        return "content.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  45 => 9,  36 => 6,  30 => 5,  27 => 4,  23 => 3,  19 => 1,);
    }
}
/* */
/* <ol>*/
/* {% for product in productsList %}*/
/*     <li>*/
/*         {{ product.name }} &mdash; {{ product.price }}<br>*/
/*         {{ product.description }}*/
/*     </li>*/
/* {% endfor %}*/
/* </ol>*/
/* */
