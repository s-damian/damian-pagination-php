<?php

declare(strict_types=1);

namespace DamianPaginationPhp;

use DamianPaginationPhp\Config\Lang;
use DamianPaginationPhp\Http\Request\Request;
use DamianPaginationPhp\Support\String\Str;
use DamianPaginationPhp\Contracts\PaginationInterface;
use DamianPaginationPhp\Contracts\Http\Request\RequestInterface;

/**
 * Rendu de la pagination.
 *
 * @author  Stephen Damian <contact@damian-freelance.fr>
 * @license http://www.opensource.org/licenses/mit-license.php MIT
 * @link    https://github.com/s-damian/damian-pagination-php
 */
abstract class RendererGenerator
{
    private const SELECTED = 'selected';

    protected PaginationInterface $pagination;

    /**
     * Pour récupérer la langue.
     *
     * @var array<string, string>
     */
    protected array $langPagination;

    final public function __construct(PaginationInterface $pagination)
    {
        $this->pagination = $pagination;

        $this->langPagination = Lang::getInstance()->pagination();
    }

    /**
     * Pour afficher la pagination.
     */
    final public function render(): string
    {
        $html = '';

        if ($this->pagination->getGetPP() !== Pagination::PER_PAGE_OPTION_ALL && $this->pagination->getCount() > $this->pagination->getPerPage()) {
            /** @var HtmlRenderer $this */
            $html .= $this->open();

            $html .= $this->previousLink();
            $html .= $this->firstLink();

            for ($i = $this->pagination->getPageStart(); $i <= $this->pagination->getPageEnd(); $i++) {
                if ($i === $this->pagination->getCurrentPage()) {
                    $html .= $this->paginationActive($i);
                } else {
                    if ($i !== 1 && $i !== $this->pagination->getNbPages()) {
                        $html .= $this->paginationLink($i);
                    }
                }
            }

            $html .= $this->lastLink();
            $html .= $this->nextLink();

            $html .= $this->close();
        }

        return $html;
    }

    /**
     * Pour choisir nombre d'éléments à afficher par page.
     *
     * @param array<string, string> $options
     * - $options['action'] string : Pour l'action du form.
     */
    final public function perPageForm(RequestInterface $request, array $options = []): string
    {
        $html = '';

        if ($this->pagination->getCount() > $this->pagination->getDefaultPerPage()) {
            // Déterminer l'URL de l'action du formulaire.
            $actionPerPage = isset($options['action']) ? (string) $options['action'] : (new Request())->getUrlCurrent();

            /** @var HtmlRenderer $this */
            $onChange = ! $request->isAjax() ? $this->perPageOnchange() : '';

            $html .= $this->perPageOpenForm($actionPerPage);
            $html .= $this->perPageLabel();
            $html .= $this->perPageOpenSelect($onChange);

            foreach ($this->pagination->getArrayOptionsSelect() as $valuePP) {
                /** @var self $this */
                $html .= $this->generateOption($valuePP);
            }

            /** @var HtmlRenderer $this */
            $html .= $this->perPageCloseSelect();
            $html .= Str::inputHiddenIfHasQueryString(['except' => [Pagination::PAGE_NAME, Pagination::PER_PAGE_NAME]]);
            $html .= $this->perPageCloseForm();
        }

        return $html;
    }

    /**
     * Génère une option pour le sélecteur de pagination.
     *
     * @param int|string $valuePP La valeur de l'option.
     * @return string Le code HTML de l'option.
     */
    private function generateOption(int|string $valuePP): string
    {
        $html = '';

        if ($this->pagination->getGetPP() !== null) {
            $selected = $valuePP === $this->pagination->getGetPP() ? self::SELECTED : '';
        } else {
            $selected = $valuePP === $this->pagination->getDefaultPerPage() ? self::SELECTED : '';
        }

        /** @var HtmlRenderer $this */
        if (
            $this->pagination->getCount() >= $valuePP &&
            $valuePP !== $this->pagination->getDefaultPerPage() &&
            $valuePP !== Pagination::PER_PAGE_OPTION_ALL
        ) {
            $html .= $this->perPageOption($selected, (string) $valuePP);
        } elseif ($valuePP === $this->pagination->getDefaultPerPage() || $valuePP === Pagination::PER_PAGE_OPTION_ALL) { // afficher ces 3 <option> en permanence
            if ($valuePP === Pagination::PER_PAGE_OPTION_ALL) {
                $html .= $this->perPageOption($selected, $valuePP, $this->langPagination[Pagination::PER_PAGE_OPTION_ALL]);
            } else {
                $html .= $this->perPageOption($selected, (string) $valuePP);
            }
        }

        return $html;
    }
}
