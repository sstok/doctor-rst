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

namespace App\Tests\Rule;

use App\Rule\BlankLineAfterFilepathInPhpCodeBlock;
use App\Tests\RstSample;
use PHPUnit\Framework\TestCase;

class BlankLineAfterFilepathInPhpCodeBlockTest extends TestCase
{
    /**
     * @test
     *
     * @dataProvider checkProvider
     */
    public function check($expected, RstSample $sample)
    {
        $this->assertSame(
            $expected,
            (new BlankLineAfterFilepathInPhpCodeBlock())->check($sample->getContent(), $sample->getLineNumber())
        );
    }

    public function checkProvider()
    {
        return [
            [
                'Please add a blank line after "// src/Handler/Collection.php"',
                new RstSample([
                    '.. code-block:: php',
                    '',
                    '    // src/Handler/Collection.php',
                    '    namespace App\\Handler;',
                ]),
            ],
            [
                null,
                new RstSample([
                    '.. code-block:: php',
                    '',
                    '    // src/Handler/Collection.php',
                    '',
                    '    namespace App\\Handler;',
                ]),
            ],
            [
                null,
                new RstSample([
                    '.. code-block:: php',
                    '',
                    '    // src/Handler/Collection.php',
                    '    // a comment',
                    '    namespace App\\Handler;',
                ]),
            ],
            [
                null,
                new RstSample([
                    '.. code-block:: php',
                    '',
                    '    // src/Handler/Collection.php',
                    '    # a comment',
                    '    namespace App\\Handler;',
                ]),
            ],
            [
                null,
                new RstSample('temp'),
            ],
        ];
    }
}