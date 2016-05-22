<?php

/* main.twig */
class __TwigTemplate_ad59d74382bcb4ab47ac41928beb6940c1c20a78b2d090379693c84eee965238 extends yii\twig\Template
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
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["this"]) ? $context["this"] : null), "beginPage", array(), "method"), "html", null, true);
        echo "
<!DOCTYPE html>
<html lang=\"";
        // line 3
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["app"]) ? $context["app"] : null), "language", array()), "html", null, true);
        echo "\">

    <head>
        ";
        // line 6
        echo $this->getAttribute((isset($context["html"]) ? $context["html"] : null), "csrfMetaTags", array(), "method");
        echo "
        <title>";
        // line 7
        echo twig_escape_filter($this->env, ((array_key_exists("title", $context)) ? (_twig_default_filter((isset($context["title"]) ? $context["title"] : null), "Shop")) : ("Shop")), "html", null, true);
        echo "</title>
        <meta charset=\"";
        // line 8
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["app"]) ? $context["app"] : null), "charset", array()), "html", null, true);
        echo "\">
        <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
        ";
        // line 10
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["this"]) ? $context["this"] : null), "head", array(), "method"), "html", null, true);
        echo "
    </head>
    
    ";
        // line 13
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["this"]) ? $context["this"] : null), "beginBody", array(), "method"), "html", null, true);
        echo "
        <body>
        
            ";
        // line 16
        echo (isset($context["content"]) ? $context["content"] : null);
        echo "
        
        </body>
    ";
        // line 19
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["this"]) ? $context["this"] : null), "endBody", array(), "method"), "html", null, true);
        echo "
    
</html>
";
        // line 22
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["this"]) ? $context["this"] : null), "endPage", array(), "method"), "html", null, true);
        echo "
";
    }

    public function getTemplateName()
    {
        return "main.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  67 => 22,  61 => 19,  55 => 16,  49 => 13,  43 => 10,  38 => 8,  34 => 7,  30 => 6,  24 => 3,  19 => 1,);
    }
}
/* {{ this.beginPage() }}*/
/* <!DOCTYPE html>*/
/* <html lang="{{ app.language }}">*/
/* */
/*     <head>*/
/*         {{ html.csrfMetaTags()|raw }}*/
/*         <title>{{ title|default('Shop') }}</title>*/
/*         <meta charset="{{ app.charset }}">*/
/*         <meta name="viewport" content="width=device-width, initial-scale=1.0">*/
/*         {{ this.head() }}*/
/*     </head>*/
/*     */
/*     {{ this.beginBody() }}*/
/*         <body>*/
/*         */
/*             {{ content|raw }}*/
/*         */
/*         </body>*/
/*     {{ this.endBody() }}*/
/*     */
/* </html>*/
/* {{ this.endPage() }}*/
/* */
