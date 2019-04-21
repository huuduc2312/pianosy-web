<?php

/* commit.twig */
class __TwigTemplate_d37cc05495efacdadbdfa029719edfb9f0615be0a809c947fc17fa4d6ac3fc6d extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 1
        $this->parent = $this->loadTemplate("layout_page.twig", "commit.twig", 1);
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
        $context["page"] = "commits";
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
        $this->loadTemplate("breadcrumb.twig", "commit.twig", 8)->display(array_merge($context, array("breadcrumbs" => array(0 => array("dir" => ("Commit " . $this->getAttribute(($context["commit"] ?? null), "hash", array())), "path" => "")))));
        // line 9
        echo "
    <div class=\"commit-view\">
        <div class=\"commit-header\">
            <span class=\"pull-right\"><a class=\"btn btn-small\" href=\"";
        // line 12
        echo twig_escape_filter($this->env, $this->env->getExtension('Symfony\Bridge\Twig\Extension\RoutingExtension')->getPath("branch", array("repo" => ($context["repo"] ?? null), "branch" => $this->getAttribute(($context["commit"] ?? null), "hash", array()))), "html", null, true);
        echo "\" title=\"Browse code at this point in history\"><i class=\"icon-list-alt\"></i> Browse code</a></span>
            <h4>";
        // line 13
        echo twig_escape_filter($this->env, $this->getAttribute(($context["commit"] ?? null), "message", array()), "html", null, true);
        echo "</h4>
        </div>
        <div class=\"commit-body\">
            ";
        // line 16
        if ( !twig_test_empty($this->getAttribute(($context["commit"] ?? null), "body", array()))) {
            // line 17
            echo "            <p>";
            echo nl2br(twig_escape_filter($this->env, $this->getAttribute(($context["commit"] ?? null), "body", array()), "html", null, true));
            echo "</p>
            ";
        }
        // line 19
        echo "            <img src=\"";
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('avatar')->getCallable(), array($this->getAttribute($this->getAttribute(($context["commit"] ?? null), "author", array()), "email", array()), 32)), "html", null, true);
        echo "\" class=\"pull-left space-right\" />
            <span>
                <a href=\"mailto:";
        // line 21
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute(($context["commit"] ?? null), "author", array()), "email", array()), "html", null, true);
        echo "\">";
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute(($context["commit"] ?? null), "author", array()), "name", array()), "html", null, true);
        echo "</a> authored on ";
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('format_date')->getCallable(), array($this->getAttribute(($context["commit"] ?? null), "date", array()))), "html", null, true);
        echo "
                ";
        // line 22
        if (($this->getAttribute($this->getAttribute(($context["commit"] ?? null), "author", array()), "email", array()) != $this->getAttribute($this->getAttribute(($context["commit"] ?? null), "commiter", array()), "email", array()))) {
            // line 23
            echo "                &bull; <a href=\"mailto:";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute(($context["commit"] ?? null), "commiter", array()), "email", array()), "html", null, true);
            echo "\">";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute(($context["commit"] ?? null), "commiter", array()), "name", array()), "html", null, true);
            echo "</a> committed on ";
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('format_date')->getCallable(), array($this->getAttribute(($context["commit"] ?? null), "commiterDate", array()))), "html", null, true);
            echo "
                ";
        }
        // line 25
        echo "                <br />Showing ";
        echo twig_escape_filter($this->env, $this->getAttribute(($context["commit"] ?? null), "changedFiles", array()), "html", null, true);
        echo " changed files
            </span>
        </div>
    </div>

    <ul class=\"commit-list\">
        ";
        // line 31
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute(($context["commit"] ?? null), "diffs", array()));
        $context['loop'] = array(
          'parent' => $context['_parent'],
          'index0' => 0,
          'index'  => 1,
          'first'  => true,
        );
        if (is_array($context['_seq']) || (is_object($context['_seq']) && $context['_seq'] instanceof Countable)) {
            $length = count($context['_seq']);
            $context['loop']['revindex0'] = $length - 1;
            $context['loop']['revindex'] = $length;
            $context['loop']['length'] = $length;
            $context['loop']['last'] = 1 === $length;
        }
        foreach ($context['_seq'] as $context["_key"] => $context["diff"]) {
            // line 32
            echo "            <li><i class=\"icon-file\"></i> <a href=\"#diff-";
            echo twig_escape_filter($this->env, $this->getAttribute($context["loop"], "index", array()), "html", null, true);
            echo "\">";
            echo twig_escape_filter($this->env, $this->getAttribute($context["diff"], "file", array()), "html", null, true);
            echo "</a> <span class=\"meta pull-right\">";
            echo twig_escape_filter($this->env, $this->getAttribute($context["diff"], "index", array()), "html", null, true);
            echo "</span></li>
        ";
            ++$context['loop']['index0'];
            ++$context['loop']['index'];
            $context['loop']['first'] = false;
            if (isset($context['loop']['length'])) {
                --$context['loop']['revindex0'];
                --$context['loop']['revindex'];
                $context['loop']['last'] = 0 === $context['loop']['revindex0'];
            }
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['diff'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 34
        echo "    </ul>

    ";
        // line 36
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute(($context["commit"] ?? null), "diffs", array()));
        $context['loop'] = array(
          'parent' => $context['_parent'],
          'index0' => 0,
          'index'  => 1,
          'first'  => true,
        );
        if (is_array($context['_seq']) || (is_object($context['_seq']) && $context['_seq'] instanceof Countable)) {
            $length = count($context['_seq']);
            $context['loop']['revindex0'] = $length - 1;
            $context['loop']['revindex'] = $length;
            $context['loop']['length'] = $length;
            $context['loop']['last'] = 1 === $length;
        }
        foreach ($context['_seq'] as $context["_key"] => $context["diff"]) {
            // line 37
            echo "    <div class=\"source-view\">
        <div class=\"source-header\">
            <div class=\"meta\"><a id=\"diff-";
            // line 39
            echo twig_escape_filter($this->env, $this->getAttribute($context["loop"], "index", array()), "html", null, true);
            echo "\">";
            echo twig_escape_filter($this->env, $this->getAttribute($context["diff"], "file", array()), "html", null, true);
            echo "</div>

            <div class=\"btn-group pull-right\">
                <a href=\"";
            // line 42
            echo twig_escape_filter($this->env, $this->env->getExtension('Symfony\Bridge\Twig\Extension\RoutingExtension')->getPath("commits", array("repo" => ($context["repo"] ?? null), "commitishPath" => (($this->getAttribute(($context["commit"] ?? null), "hash", array()) . "/") . $this->getAttribute($context["diff"], "file", array())))), "html", null, true);
            echo "\" class=\"btn btn-small\"><i class=\"icon-list-alt\"></i> History</a>
                <a href=\"";
            // line 43
            echo twig_escape_filter($this->env, $this->env->getExtension('Symfony\Bridge\Twig\Extension\RoutingExtension')->getPath("blob", array("repo" => ($context["repo"] ?? null), "commitishPath" => (($this->getAttribute(($context["commit"] ?? null), "hash", array()) . "/") . $this->getAttribute($context["diff"], "file", array())))), "html", null, true);
            echo "\" class=\"btn btn-small\"><i class=\"icon-file\"></i> View file @ ";
            echo twig_escape_filter($this->env, $this->getAttribute(($context["commit"] ?? null), "shortHash", array()), "html", null, true);
            echo "</a>
            </div>
        </div>

        <div class=\"source-diff\">
        <table>
        ";
            // line 49
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable($this->getAttribute($context["diff"], "getLines", array()));
            $context['loop'] = array(
              'parent' => $context['_parent'],
              'index0' => 0,
              'index'  => 1,
              'first'  => true,
            );
            if (is_array($context['_seq']) || (is_object($context['_seq']) && $context['_seq'] instanceof Countable)) {
                $length = count($context['_seq']);
                $context['loop']['revindex0'] = $length - 1;
                $context['loop']['revindex'] = $length;
                $context['loop']['length'] = $length;
                $context['loop']['last'] = 1 === $length;
            }
            foreach ($context['_seq'] as $context["_key"] => $context["line"]) {
                // line 50
                echo "            <tr>
                <td class=\"lineNo\">
                    ";
                // line 52
                if (($this->getAttribute($context["line"], "getType", array()) != "chunk")) {
                    // line 53
                    echo "                        <a name=\"L";
                    echo twig_escape_filter($this->env, $this->getAttribute($context["loop"], "index", array()), "html", null, true);
                    echo "R";
                    echo twig_escape_filter($this->env, $this->getAttribute($context["line"], "getNumOld", array()), "html", null, true);
                    echo "\"></a>
                        <a href=\"#L";
                    // line 54
                    echo twig_escape_filter($this->env, $this->getAttribute($context["loop"], "index", array()), "html", null, true);
                    echo "R";
                    echo twig_escape_filter($this->env, $this->getAttribute($context["line"], "getNumOld", array()), "html", null, true);
                    echo "\">
                    ";
                }
                // line 56
                echo "                    ";
                echo twig_escape_filter($this->env, $this->getAttribute($context["line"], "getNumOld", array()), "html", null, true);
                echo "
                    ";
                // line 57
                if (($this->getAttribute($context["line"], "getType", array()) != "chunk")) {
                    // line 58
                    echo "                        </a>
                    ";
                }
                // line 60
                echo "                </td>
                <td class=\"lineNo\">
                    ";
                // line 62
                if (($this->getAttribute($context["line"], "getType", array()) != "chunk")) {
                    // line 63
                    echo "                        <a name=\"L";
                    echo twig_escape_filter($this->env, $this->getAttribute($context["loop"], "index", array()), "html", null, true);
                    echo "L";
                    echo twig_escape_filter($this->env, $this->getAttribute($context["line"], "getNumNew", array()), "html", null, true);
                    echo "\"></a>
                        <a href=\"#L";
                    // line 64
                    echo twig_escape_filter($this->env, $this->getAttribute($context["loop"], "index", array()), "html", null, true);
                    echo "L";
                    echo twig_escape_filter($this->env, $this->getAttribute($context["line"], "getNumNew", array()), "html", null, true);
                    echo "\">
                    ";
                }
                // line 66
                echo "                    ";
                echo twig_escape_filter($this->env, $this->getAttribute($context["line"], "getNumNew", array()), "html", null, true);
                echo "
                    ";
                // line 67
                if (($this->getAttribute($context["line"], "getType", array()) != "chunk")) {
                    // line 68
                    echo "                        </a>
                    ";
                }
                // line 70
                echo "                </td>
                <td style=\"width: 100%\">
                    <pre";
                // line 72
                if ($this->getAttribute($context["line"], "getType", array())) {
                    echo " class=\"";
                    echo twig_escape_filter($this->env, $this->getAttribute($context["line"], "getType", array()), "html", null, true);
                    echo "\"";
                }
                echo ">";
                echo twig_escape_filter($this->env, $this->getAttribute($context["line"], "getLine", array()), "html", null, true);
                echo "</pre>
                </td>
            </tr>
        ";
                ++$context['loop']['index0'];
                ++$context['loop']['index'];
                $context['loop']['first'] = false;
                if (isset($context['loop']['length'])) {
                    --$context['loop']['revindex0'];
                    --$context['loop']['revindex'];
                    $context['loop']['last'] = 0 === $context['loop']['revindex0'];
                }
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['line'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 76
            echo "        </table>
        </div>
    </div>
    ";
            ++$context['loop']['index0'];
            ++$context['loop']['index'];
            $context['loop']['first'] = false;
            if (isset($context['loop']['length'])) {
                --$context['loop']['revindex0'];
                --$context['loop']['revindex'];
                $context['loop']['last'] = 0 === $context['loop']['revindex0'];
            }
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['diff'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 80
        echo "
    <hr />
";
    }

    public function getTemplateName()
    {
        return "commit.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  313 => 80,  296 => 76,  272 => 72,  268 => 70,  264 => 68,  262 => 67,  257 => 66,  250 => 64,  243 => 63,  241 => 62,  237 => 60,  233 => 58,  231 => 57,  226 => 56,  219 => 54,  212 => 53,  210 => 52,  206 => 50,  189 => 49,  178 => 43,  174 => 42,  166 => 39,  162 => 37,  145 => 36,  141 => 34,  120 => 32,  103 => 31,  93 => 25,  83 => 23,  81 => 22,  73 => 21,  67 => 19,  61 => 17,  59 => 16,  53 => 13,  49 => 12,  44 => 9,  41 => 8,  38 => 7,  32 => 5,  28 => 1,  26 => 3,  11 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "commit.twig", "/home/ypx/public_html/pi/docroot/gitlist/themes/default/twig/commit.twig");
    }
}
