<?php
/**
 * This file is part of the Moontoast\Math library
 *
 * Copyright 2013-2016 Moontoast, Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * @copyright 2013-2016 Moontoast, Inc.
 * @license https://github.com/ramsey/moontoast-math/blob/master/LICENSE Apache 2.0
 */

namespace Moontoast\Math\Exception;

/**
 * Thrown when an exceptional arithmetic condition has occurred. For example,
 * an integer "divide by zero" throws an instance of this class.
 */
class ArithmeticException extends \RuntimeException
{
}
