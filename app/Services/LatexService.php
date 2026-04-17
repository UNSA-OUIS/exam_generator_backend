<?php
namespace App\Services;

use App\Models\Question;

class LatexService
{
    public function masterHeader(string $area): string
    {
        return "\\documentclass[11pt,twocolumn,twoside]{article}
    \\usepackage[papersize={215mm,320mm},tmargin=18mm,bmargin=32mm,lmargin=15mm,rmargin=15mm]{geometry}
    \\usepackage{epsfig}
    \\usepackage{amsmath}
    \\usepackage{latexsym}
    \\usepackage{amssymb}
    \\usepackage[spanish]{babel}
    \\usepackage{fancyhdr}
    \\usepackage{cmbright}
    \\usepackage{pb-diagram}
    \\usepackage{enumitem}
    \\setlength{\\columnsep}{.7cm} \\pagestyle{fancy}
    \\fancyhead[LE,RO]{\\textsf{MASTER}}
    \\fancyhead[LO,RE]{\\scriptsize{\\textbf{Bloques y niveles}}}
    \\chead{Area: \\huge{\\textrm{{$area}}}}
    \\fancyfoot[LE,RO]{\\Large{\\textbf{\\textsf{\\thepage}}}}
    \\fancyfoot[LO,RE]{\\vspace {-4mm}
    \\includegraphics[scale=0.6]{logounsa.eps}}
    \\cfoot{\\scriptsize{\\textbf{" . now('America/Lima')->translatedFormat('l j \d\e F Y') . "}}}
    \\clubpenalty=10000 \\widowpenalty=10000

    \\begin{document}";
    }

    public function examHeader($exam, string $title, string $variation): string
    {
        return "\\documentclass[11pt,twocolumn,twoside]{article}
    \\usepackage[papersize={215mm,320mm},tmargin=18mm,bmargin=32mm,lmargin=15mm,rmargin=15mm]{geometry}
    \\usepackage{epsfig}
    \\usepackage{amsmath}
    \\usepackage{latexsym}
    \\usepackage{amssymb}
    \\usepackage[spanish]{babel}
    \\usepackage{fancyhdr}
    \\usepackage{cmbright}
    \\usepackage{pb-diagram}
    \\usepackage{enumitem}
    \\setlength{\\columnsep}{.7cm} \\pagestyle{fancy}
    \\fancyhead[LE,RO]{\\textsf{Tema: \\Large{\\textsf{{\\textbf{" . $variation . "}}}}}}
    \\fancyhead[LO,RE]{\\scriptsize{\\textbf{" . $exam->description . "}}} \\chead{\'{A}rea: \\huge{\\textrm{" . $title . "}}}
    \\fancyfoot[LE,RO]{\\Large{\\textbf{\\textsf{\\thepage}}}}
    \\fancyfoot[LO,RE]{\\vspace {-4mm}
    \\includegraphics[scale=0.6]{logounsa.eps}}
    \\cfoot{\\scriptsize{\\textbf{" . now('America/Lima')->translatedFormat('l j \d\e F Y') . "}}} \\clubpenalty=10000 \\widowpenalty=10000

    \\begin{document}
    \\begin{enumerate}[label=\\textbf{\arabic*}.,start=1]\n";
    }

    public function buildMaster($questions, string $area): string
    {
        $latex = $this->masterHeader($area);
        $latex .= "\\begin{enumerate}[label=\\textbf{\\arabic*.},start=1]\n";

        $lastBlock = null;
        $textsCompiled = [];

        foreach ($questions as $question) {

            if ($question->block_id !== $lastBlock) {
                $latex .= "\\subsubsection*{" . strtoupper($question->block->name ?? '') . "}\n";
                $lastBlock = $question->block_id;
            }

            if ($question->text && !in_array($question->text_id, $textsCompiled)) {
                $textsCompiled[] = $question->text_id;
                $latex .= "% TEXT\n{$question->text->content}\n";
            }

            $latex .= $this->renderQuestion($question);
        }

        return $latex . "\\end{enumerate}\n\\end{document}";
    }

    public function buildVariation($exam, $layout, $area, $variation): string
    {
        $latex = $this->examHeader($exam, $area, $variation);
        $textsCompiled = [];

        foreach ($layout as $l) {
            $q = $l->question;

            if ($q->text_id && !in_array($q->text_id, $textsCompiled)) {
                $textsCompiled[] = $q->text_id;
                $latex .= "% TEXT\n{$q->text->content}\n";
            }

            $latex .= $this->renderQuestion($q, $l->options_shuffled);
        }

        return $latex . "\\end{enumerate}\n\\end{document}";
    }

    public function renderQuestion(Question $q, ?array $order = null): string
    {
        $options = $q->options->sortBy('number')->values();

        // If no order provided → default 1..N
        if (is_null($order)) {
            $order = range(1, $options->count());
        }

        // Optional safety check (recommended)
        if (count($order) !== $options->count()) {
            throw new \InvalidArgumentException(
                "Option order count does not match options count for question {$q->id}"
            );
        }

        $latex = "\\item {$q->statement}\n\\begin{description}\n";

        foreach ($order as $i => $pos) {
            $opt = $options[$pos - 1]; // positions are 1-based
            $letter = chr(65 + $i);    // A, B, C...

            $latex .= "\\item[{$letter}.] {$opt->description}\n";
        }

        return $latex . "\\end{description}\n\\pagebreak[2]\n";
    }
}