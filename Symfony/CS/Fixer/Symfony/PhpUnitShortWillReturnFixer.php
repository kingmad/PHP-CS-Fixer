<?php

/*
 * This file is part of PHP CS Fixer.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *     Dariusz Rumiński <dariusz.ruminski@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Symfony\CS\Fixer\Symfony;

use Symfony\CS\AbstractFixer;
use Symfony\CS\Tokenizer\Token;
use Symfony\CS\Tokenizer\Tokens;

/**
 * PHPUnit Short Will Return Fixer.
 */
class PhpUnitShortWillReturnFixer extends AbstractFixer
{
    const WILL_RETURN_CONTENT = array(
        T_CONSTANT_ENCAPSED_STRING,
        T_VARIABLE,
        T_CONST,
    );

    /**
     * {@inheritdoc}
     */
    public function fix(\SplFileInfo $file, $content)
    {
        $index = 1;
        $tokens = Tokens::fromCode($content);

        foreach ($this->getSequences() as $sequence) {
            $this->fixSequence($tokens, $sequence, $index);
        }

        $tokens->clearEmptyTokens();

        return $tokens->generateCode();
    }

    /**
     * {@inheritdoc}
     */
    public function getDescription()
    {
        return 'xxxx';
    }

    /**
     * @return array
     */
    public function getSequences()
    {
        $sequences = array();

        foreach (self::WILL_RETURN_CONTENT as $item) {
            $sequences[] = array(
                array(T_OBJECT_OPERATOR, '->'),
                array(T_STRING, 'will'),
                '(',
                array(T_VARIABLE, '$this'),
                array(T_OBJECT_OPERATOR, '->'),
                array(T_STRING, 'returnValue'),
                '(',
                array($item),
                ')',
                ')',
            );
        }

        return $sequences;
    }

    /**
     * @param Tokens $tokens
     * @param array  $sequence
     * @param int    $index
     */
    private function fixSequence(Tokens $tokens, $sequence, $index)
    {
        $occurrence = $tokens->findSequence($sequence, $index);
        while (null !== $occurrence) {
            $index = $this->fixOccurrence($tokens, $occurrence);
            $occurrence = $tokens->findSequence($sequence, ++$index);
        }
    }

    /**
     * @param Tokens $tokens
     * @param array  $occurrence
     *
     * @return
     */
    public function fixOccurrence(Tokens $tokens, array $occurrence)
    {
        $sequenceIndexes = array_keys($occurrence);
        $tokens->clearRange($sequenceIndexes[2], $sequenceIndexes[5]);
        $tokens->overrideAt($sequenceIndexes[9], '');
        /** @var Token $token */
        $token = $tokens[$sequenceIndexes[1]];
        $token->setContent('willReturn');

        return $sequenceIndexes[9];
    }
}
