<?php

namespace Wa\MemberBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class WaMemberBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
