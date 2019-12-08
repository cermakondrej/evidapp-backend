<?php
declare(strict_types=1);

namespace App\Util;

use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class Violation
{

    public function build(ConstraintViolationListInterface $violations): array
    {
        $errors = [];

        /** @var ConstraintViolation $violation */
        foreach ($violations as $violation) {
            $errors[$this->makeSnakeCase($violation->getPropertyPath())] = $violation->getMessage();
        }

        return $this->buildMessages($errors);
    }

    private function buildMessages(array $errors): array
    {
        $result = [];

        foreach ($errors as $path => $message) {
            $temp = &$result;

            foreach (explode('.', $path) as $key) {
                preg_match('/(.*)(\[.*?\])/', $key, $matches);
                if ($matches) {
                    $index = str_replace(['[', ']'], '', $matches[2]);
                    $temp = &$temp[$matches[1]][$index];
                } else {
                    $temp = &$temp[$key];
                }
            }

            $temp = $message;
        }

        return $result;
    }

    private function makeSnakeCase(string $text): string
    {
        if (!trim($text)) {
            return $text;
        }

        return strtolower((string) preg_replace('~(?<=\\w)([A-Z])~', '_$1', $text));
    }
}
