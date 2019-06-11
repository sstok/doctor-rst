<?php

declare(strict_types=1);

/*
 * This file is part of DOCtor-RST.
 *
 * (c) Oskar Stark <oskarstark@googlemail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Rule;

use App\Annotations\Rule\Description;
use App\Annotations\Rule\InvalidExample;
use App\Annotations\Rule\ValidExample;
use App\Handler\Registry;
use App\Rst\RstParser;
use App\Value\RuleGroup;

/**
 * @Description("Make sure to only use `yaml` instead of `yml`.")
 * @ValidExample({".travis.yml", "..code-block:: yaml", "Please add this to your services.yaml file."})
 * @InvalidExample({"..code-block:: yml", "Please add this to your services.yml file."})
 */
class YamlInsteadOfYmlSuffix extends AbstractRule implements Rule
{
    public static function getGroups(): array
    {
        return [
            RuleGroup::fromString(Registry::GROUP_SONATA),
            RuleGroup::fromString(Registry::GROUP_SYMFONY),
        ];
    }

    public function check(\ArrayIterator $lines, int $number)
    {
        $lines->seek($number);
        $line = $lines->current();

        if (preg_match('/\.travis\.yml/', $line)) {
            return;
        }

        if (RstParser::codeBlockDirectiveIsTypeOf($line, RstParser::CODE_BLOCK_YML)) {
            return 'Please use ".. code-block:: yaml" instead of ".. code-block:: yml"';
        }

        if (preg_match('/\.yml/i', $line, $matches)) {
            return sprintf('Please use ".yaml" instead of "%s"', $matches[0]);
        }
    }
}
