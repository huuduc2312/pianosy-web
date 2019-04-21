<?php

/* treegraph.twig */
class __TwigTemplate_5b966784c3668e22f7ab49b46586e1abd0950df5d674c282589c37edfed05397 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 1
        $this->parent = $this->loadTemplate("layout_page.twig", "treegraph.twig", 1);
        $this->blocks = array(
            'title' => array($this, 'block_title'),
            'content' => array($this, 'block_content'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "layout_page.twig";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 3
        $context["page"] = "treegraph";
        // line 1
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 5
    public function block_title($context, array $blocks = array())
    {
        echo "GitList";
    }

    // line 7
    public function block_content($context, array $blocks = array())
    {
        // line 8
        echo "    ";
        $this->loadTemplate("breadcrumb.twig", "treegraph.twig", 8)->display(array_merge($context, array("breadcrumbs" => array(0 => array("dir" => "Graph", "path" => "")))));
        // line 9
        echo "    <div class=\"network-view\">
        <div class=\"network-header\">
            <div class=\"meta\">Graph of ";
        // line 11
        echo twig_escape_filter($this->env, ($context["repo"] ?? null), "html", null, true);
        echo " </div>
        </div>

        <div id=\"git-graph-container\">
            <div id=\"rel-container\">
                <canvas id=\"graph-canvas\" width=\"100px\">
                    <ul id=\"graph-raw-list\">
                        ";
        // line 18
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(($context["graphItems"] ?? null));
        foreach ($context['_seq'] as $context["_key"] => $context["item"]) {
            // line 19
            echo "                            <li><span class=\"node-relation\">";
            echo twig_escape_filter($this->env, $this->getAttribute($context["item"], "relation", array()), "html", null, true);
            echo "</span></li>
                        ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['item'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 21
        echo "                    </ul>
                </canvas>
            </div>
            <div style=\"float:left;\" id=\"rev-container\">
                <ul id=\"rev-list\">
                    ";
        // line 26
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(($context["graphItems"] ?? null));
        foreach ($context['_seq'] as $context["_key"] => $context["item"]) {
            // line 27
            echo "                        <li>
                            ";
            // line 28
            if ($this->getAttribute($context["item"], "rev", array(), "any", true, true)) {
                // line 29
                echo "                                <a id=\"";
                echo twig_escape_filter($this->env, $this->getAttribute($context["item"], "short_rev", array()), "html", null, true);
                echo "\" class=\"btn btn-small\" href=\"";
                echo twig_escape_filter($this->env, $this->env->getExtension('Symfony\Bridge\Twig\Extension\RoutingExtension')->getPath("commit", array("repo" => ($context["repo"] ?? null), "commit" => $this->getAttribute($context["item"], "rev", array()))), "html", null, true);
                echo "\"> ";
                echo twig_escape_filter($this->env, $this->getAttribute($context["item"], "short_rev", array()), "html", null, true);
                echo " </a>
                                <strong> ";
                // line 30
                echo twig_escape_filter($this->env, $this->getAttribute($context["item"], "branch", array()), "html", null, true);
                echo " </strong>
                                <em>";
                // line 31
                echo twig_escape_filter($this->env, $this->getAttribute($context["item"], "subject", array()), "html", null, true);
                echo "</em> by
                                <span class=\"author\">";
                // line 32
                echo twig_escape_filter($this->env, $this->getAttribute($context["item"], "author", array()), "html", null, true);
                echo " &lt;";
                echo twig_escape_filter($this->env, $this->getAttribute($context["item"], "author_email", array()), "html", null, true);
                echo "&gt;</span>
                                <span class=\"time\">";
                // line 33
                echo twig_escape_filter($this->env, $this->getAttribute($context["item"], "date", array()), "html", null, true);
                echo "</span>;
                            ";
            } else {
                // line 35
                echo "                                <span/>
                            ";
            }
            // line 37
            echo "                        </li>
                    ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['item'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 39
        echo "                </ul>
            </div>
            <div style=\"clear:both\"><!-- --></div>
        </div>
    </div>



    <hr/>
";
    }

    public function getTemplateName()
    {
        return "treegraph.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  126 => 39,  119 => 37,  115 => 35,  110 => 33,  104 => 32,  100 => 31,  96 => 30,  87 => 29,  85 => 28,  82 => 27,  78 => 26,  71 => 21,  62 => 19,  58 => 18,  48 => 11,  44 => 9,  41 => 8,  38 => 7,  32 => 5,  28 => 1,  26 => 3,  11 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "treegraph.twig", "/home/ypx/public_html/pi/docroot/gitlist/themes/default/twig/treegraph.twig");
    }
}
