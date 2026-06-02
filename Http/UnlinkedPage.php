<?php

/**
 * webtrees: online genealogy
 * Copyright (C) 2026 webtrees development team
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <https://www.gnu.org/licenses/>.
 */

declare(strict_types=1);

namespace Hartenthaler\Webtrees\Module\UnlinkedIndividual\Http;

use Hartenthaler\Webtrees\Module\UnlinkedIndividual\UnlinkedIndividualModule;
use Fisharebest\Webtrees\Http\Exceptions\HttpAccessDeniedException;
use Fisharebest\Webtrees\Http\ViewResponseTrait;
use Fisharebest\Webtrees\Http\RequestHandlers\TreePage;
use Fisharebest\Webtrees\I18N;
use Fisharebest\Webtrees\Registry;
use Fisharebest\Webtrees\Services\GedcomEditService;
use Fisharebest\Webtrees\Tree;
use Fisharebest\Webtrees\Validator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

use function assert;
use function route;

/**
 * Create a new unlinked individual.
 */
class UnlinkedPage implements RequestHandlerInterface
{
    use ViewResponseTrait;

    public function __construct(
        private readonly GedcomEditService $gedcom_edit_service,
    ) {
    }

    /**
     * @param ServerRequestInterface $request
     *
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $tree = $request->getAttribute('tree');
        assert($tree instanceof Tree);

        if (!UnlinkedIndividualModule::canCreateUnlinkedIndividual($tree)) {
            throw new HttpAccessDeniedException();
        }

        $sex = Registry::elementFactory()->make('INDI:SEX')->default($tree);
        $name = Registry::elementFactory()->make('INDI:NAME')->default($tree);
        $url = route(TreePage::class, ['tree' => $tree->name()]);

        return $this->viewResponse('edit/new-individual', [
            'facts'               => [
                'i' => $this->gedcom_edit_service->newIndividualFacts($tree, $sex, ['1 NAME ' . $name]),
            ],
            'gedcom_edit_service' => $this->gedcom_edit_service,
            'post_url'            => route(UnlinkedAction::class, ['tree' => $tree->name()]),
            'tree'                => $tree,
            'title'               => I18N::translate('Create an unlinked individual'),
            'url'                 => Validator::queryParams($request)->isLocalUrl()->string('url', $url),
        ]);
    }
}
