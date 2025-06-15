<?php

declare(strict_types=1);

namespace Selmak\Proaxive2\Domain\Customer;

final class CustomerPwdDTO
{
    public function __construct(
      public ?string $passwd = null,
      public ?string $passwd_2 = null,
    ){}
}