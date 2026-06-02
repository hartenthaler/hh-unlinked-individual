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
use Fisharebest\Webtrees\Individual;
use Fisharebest\Webtrees\Http\Exceptions\HttpAccessDeniedException;
use Fisharebest\Webtrees\Tree;
use Fisharebest\Webtrees\Services\GedcomEditService;
use Fisharebest\Webtrees\Validator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

use function assert;
use function redirect;

/**
 * Create a new unlinked individual.
 */
class UnlinkedAction implements RequestHandlerInterface
{
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

        $levels = Validator::parsedBody($request)->array('ilevels');
        $tags = Validator::parsedBody($request)->array('itags');
        $values = Validator::parsedBody($request)->array('ivalues');
        $gedcom = $this->gedcom_edit_service->editLinesToGedcom(Individual::RECORD_TYPE, $levels, $tags, $values);
        $individual = $tree->createIndividual('0 @@ INDI' . $gedcom);

        $url = Validator::parsedBody($request)->isLocalUrl()->string('url', $individual->url());

        return redirect($url);
    }
}
