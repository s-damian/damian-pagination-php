<?php

namespace DamianPaginationPhp;

use DamianPaginationPhp\Config\Lang;
use DamianPaginationPhp\Support\String\Str;
use DamianPaginationPhp\Support\Facades\Request;
use DamianPaginationPhp\Contracts\PaginationInterface;
use DamianPaginationPhp\Contracts\Support\Request\RequestInterface;

/**
 * Rendu de la pagination.
 *
 * @author  Stephen Damian <contact@devandweb.fr>
 * @license http://www.opensource.org/licenses/mit-license.php MIT
 * @link    https://github.com/s-damian/damian-pagination-php
 */
abstract class RendererGenerator
{
    protected PaginationInterface $pagination;

    /**
     * Pour récupérer la langue.
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
     * @param array $options
     * - $options['action'] string : Pour l'action du form.
     */
    final public function perPageForm(RequestInterface $request, array $options = []): string
    {
        $html = '';

        if ($this->pagination->getCount() > $this->pagination->getDefaultPerPage()) {
            $actionPerPage = isset($options['action']) && is_string($options['action']) ? $options['action'] : Request::getUrlCurrent();

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

    private function generateOption(int|string $valuePP): string
    {
        $html = '';

        $selectedPP = $valuePP === $this->pagination->getGetPP()
            ? 'selected'
            : '';

        $selectedDefault = $this->pagination->getGetPP() === null && $valuePP === $this->pagination->getDefaultPerPage()
            ? 'selected'
            : '';

        /** @var HtmlRenderer $this */
        if (
            $this->pagination->getCount() >= $valuePP &&
            $valuePP !== $this->pagination->getDefaultPerPage() &&
            $valuePP !== Pagination::PER_PAGE_OPTION_ALL
        ) {
            $html .= $this->perPageOption($selectedDefault.$selectedPP, $valuePP);
        } elseif ($valuePP === $this->pagination->getDefaultPerPage() || $valuePP === Pagination::PER_PAGE_OPTION_ALL) { // afficher ces 3 <option> en permanance
            if ($valuePP === Pagination::PER_PAGE_OPTION_ALL) {
                $html .= $this->perPageOption($selectedDefault.$selectedPP, $valuePP, $this->langPagination[Pagination::PER_PAGE_OPTION_ALL]);
            } else {
                $html .= $this->perPageOption($selectedDefault.$selectedPP, $valuePP);
            }
        }

        return $html;
    }
}
