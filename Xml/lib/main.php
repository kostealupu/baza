<?php
use Df\Core\Exception as E;
use Df\Xml\X;
use Magento\Framework\Simplexml\Element as MX;
use SimpleXMLElement as CX;

/**
 * @param CX $e
 * @return CX
 * @throws E
 */
function df_assert_leaf(CX $e) {return df_check_leaf($e) ? $e : df_error(
	"Требуется лист XML, однако получена ветка XML:\n%s.", df_xml_report($e)
);}

/**
 * @see df_needs_cdata()
 * @used-by df_clean_xml()
 * @used-by \Dfe\SecurePay\Refund::process()
 * @param string $s
 * @return string
 */
function df_cdata($s) {return X::markAsCData($s);}

/**
 * 2021-12-16
 * @used-by \Df\Xml\X::asNiceXml()
 * @param string $s
 * @return string
 */
function df_cdata_raw_if_needed($s) {return !df_needs_cdata($s) ? $s : "<![CDATA[$s]]>";}

/**
 * 2015-02-27
 * Обратите внимание,
 * что метод @see \SimpleXMLElement::count() появился только в PHP 5.3,
 * поэтому мы его не используем: http://php.net/manual/simplexmlelement.count.php
 * Также обратите внимание, что count($e->children())
 * некорректно возвращает 1 для листов в PHP 5.1: http://3v4l.org/PT6Pt
 * Однако нам не нужно поддерживать PHP 5.1.
 *
 * Обратите внимание, что для несуществующего узла попытка вызова @uses count()
 * привелёт к сбою: «Warning: count(): Node no longer exists»
 * http://3v4l.org/PsIPe#v512
 *
 * Текущий алгоритм проверен на работоспособность здесь: http://3v4l.org/VldTN
 *
 * 2015-08-16
 *
 * Как ни странно, написанное выше действительно верно: http://3v4l.org/covo1
 *
 * Обратите внимение, что класс @see \SimpleXMLElement не реализует интерфейс @see Iterator,
 * а реализует только интерфейс @see Traversable.
 * http://php.net/manual/class.iterator.php
 * http://php.net/manual/class.traversable.php
 * https://php.net/manual/simplexmlelement.count.php
 * Однако @uses count() почему-то работает для него.
 * @see \SimpleXMLElement — самый загадочный класс PHP.
 *
 * 2015-08-15
 * Нельзя здесь использовать count($e->children()),
 * потому что класс @see SimpleXmlElement не реализует интерфейс @see Iterator,
 * а реализует только интерфейс @see Traversable.
 * http://php.net/manual/class.iterator.php
 * http://php.net/manual/class.traversable.php
 * https://php.net/manual/simplexmlelement.count.php
 *
 * @param CX $e
 * @return bool
 */
function df_check_leaf(CX $e) {return !df_xml_exists($e) || !$e->children()->count();}

/**
 * 2016-09-01
 * Вообще говоря, заголовок у XML необязателен,
 * но моя функция @see df_xml_prettify() его добавляет,
 * поэтому меня пока данный алгоритм устраивает.
 * Более качественный алгоритм будет более ресурсоёмким: нам надо будет разбирать весь XML.
 * @param mixed $v
 * @return bool
 */
function df_check_xml($v) {return is_string($v) && df_starts_with($v, '<?xml');}

/**
 * 2015-02-27
 * Обратите внимание на разницу между @see \SimpleXMLElement::asXML()
 * и @see \SimpleXMLElement::__toString() / оператор (string)$this.
 *
 * @see \SimpleXMLElement::__toString() и (string)$this
 * возвращают непустую строку только для концевых узлов (листьев дерева XML).
 * Пример:
 *	<?xml version='1.0' encoding='utf-8'?>
 *		<menu>
 *			<product>
 *				<cms>
 *					<class>aaa</class>
 *					<weight>1</weight>
 *				</cms>
 *				<test>
 *					<class>bbb</class>
 *					<weight>2</weight>
 *				</test>
 *			</product>
 *		</menu>
 * Здесь для $e1 = $xml->{'product'}->{'cms'}->{'class'}
 * мы можем использовать $e1->__toString() и (string)$e1.
 * http://3v4l.org/rAq3F
 * Однако для $e2 = $xml->{'product'}->{'cms'}
 * мы не можем использовать $e2->__toString() и (string)$e2,
 * потому что узел «cms» не является концевым узлом (листом дерева XML).
 * http://3v4l.org/Pkj37
 * Более того, метод @see \SimpleXMLElement::__toString()
 * отсутствует в PHP версий 5.2.17 и ниже:
 * http://3v4l.org/Wiia2#v500
 *
 * 2015-03-02
 * Обратите внимание,
 * то мы специально допускаем возможность для первого параметра $e принимать значение null:
 * это даёт нам возможность писать код типа:
 * @used-by Df_Page_Helper_Head::needSkipAsStandardCss()
 *	df_leaf_b(df_config_node(
 *		'df/page/skip_standard_css/', df_state()->getController()->getFullActionName()
 *	))
 * без дополнительных проверок, имеется ли в наличии запрашиваемый лист дерева XML
 * (если лист отсутствует, то @see df_config_node() вернёт null)
 *
 * @used-by df_leaf_b()
 * @used-by df_leaf_child()
 * @used-by df_leaf_f()
 * @used-by df_leaf_i()
 * @used-by df_leaf_s()
 * @param CX|null $e [optional]
 * @param string|null|callable $d [optional]
 * @return string|null
 */
function df_leaf(CX $e = null, $d = null) {/** @var string $r */
	/**
	 * 2015-08-04
	 * Нельзя здесь использовать !$e,
	 * потому что для концевых текстовых узлов с ненулевым целым значением (например: «147»)
	 * такое выражение довольно-таки неожиданно возвращает true.
	 * @see \SimpleXMLElement вообще необычный класс с нестандартным поведением.
	 * Чтобы понять, почему в данном случае !$e равно true, посморите функцию @see df_xml_exists()
	 *
	 * Так вот, @see df_xml_exists() для текстового узла всегда возвращает false,
	 * даже если текстовое значение не приводится к false (то же «147»).
	 *
	 * Почему так происходит — видно из реализации @see df_xml_exists(): !empty($e)
	 * То есть, empty($e) для текстовых узлов возвращает true.
	 *
	 * Например:
	 *	<Остаток>
	 *		<Склад>
	 *			<Ид>6f87e83f-722c-11df-b336-0011955cba6b</Ид>
	 *			<Количество>147</Количество>
	 *		</Склад>
	 *	</Остаток>
	 * Если здесь сделать xpath Остаток/Склад/Количество,
	 * то для узла «147» !$e почему-то вернёт true,
	 * хотя в данном случае $e является полноценным объектом @see \SimpleXMLElement
	 * и (string)$e возвращает «147».
	 */
	if (is_null($e)) {
		$r = df_call_if($d);
	}
	else if (df_es($r = (string)df_assert_leaf($e))) {
		/**
		 * 2015-09-25
		 * Добавил данное условие, чтобы различать случай пустого узла и отсутствия узла.
		 * Пример пустого узла ru_RU:
		 * <term>
		 * 		<en_US>Order Total</en_US>
		 * 		<ru_RU></ru_RU>
		 * </term>
		 * Так вот, для пустого узла empty($e) вернёт false,
		 * а для отсутствующего узла — true.
		 */
		$r = df_if1(empty($e), $d, '');
	}
	return $r;
}

/**
 * @deprecated It is unused.
 * @param CX|null $e [optional]
 * @param bool $default|callable [optional]
 * @return bool
 */
function df_leaf_b(CX $e = null, $default = false) {return df_bool(df_leaf($e, $default));}

/**
 * @param CX $e
 * @param string $child
 * @param string|mixed|null|callable $d [optional]
 * @return string|mixed|null
 */
function df_leaf_child(CX $e, $child, $d = null) {return df_leaf($e->{$child}, $d);}

/**
 * 2015-08-16 Намеренно убрал параметр $default.
 * @param CX|null $e [optional]
 * @return float
 */
function df_leaf_f(CX $e = null) {return df_float(df_leaf($e));}

/**
 * 2015-08-16
 * Намеренно убрал параметр $default.
 * @param CX|null $e [optional]
 * @return int
 */
function df_leaf_i(CX $e = null) {return df_int(df_leaf($e));}

/**
 * @used-by df_leaf_sne()
 * @used-by \Df\Xml\X::map()
 * @used-by \Df\Xml\X::xpathMap()
 * @used-by \Dfe\Robokassa\Api\Options::p()
 * @param CX|null $e [optional]
 * @param string $d|callable [optional]
 * @return string
 */
function df_leaf_s(CX $e = null, $d = '') {return (string)df_leaf($e, $d);}

/**
 * @used-by \Df\Xml\X::map()
 * @used-by \Df\Xml\X::xpathMap()
 * @used-by \Dfe\SecurePay\Refund::process()
 * @param CX|null $e [optional]
 * @param string $d|callable [optional]
 * @return string
 */
function df_leaf_sne(CX $e = null, $d = '') {/** @var string $r */
	if (df_es($r = df_leaf_s($e, $d))) {
		df_error('Лист дерева XML должен быть непуст, однако он пуст.');
	}
	return $r;
}

/**
 * 2021-12-12 https://3v4l.org/3SDsT
 * @see df_cdata()
 * @used-by df_cdata_raw_if_needed()
 * @used-by \Df\Xml\X::importString()
 * @param string $s
 * @return bool
 */
function df_needs_cdata($s) {
	$s1 = htmlspecialchars_decode($s, ENT_XML1 | ENT_QUOTES);
    $s2 = htmlspecialchars($s1, ENT_XML1 | ENT_NOQUOTES);
	return $s !== $s2 && $s1 !== $s2;
}

/**
 * @deprecated It is unused.
 * @param CX $e
 * @param string $name
 * @param bool $req [optional]
 * @return CX|null
 * @throws E
 */
function df_xml_child(CX $e, $name, $req = false) {
	$childNodes = df_xml_children($e, $name, $req); /** @var CX[] $childNodes */
	if (is_null($childNodes)) { /** @var CX|null $r */
		$r = null;
	}
	else {
		/**
		 * Обратите внимание, что если мы имеем структуру:
		 *	<dictionary>
		 *		<rule/>
		 *		<rule/>
		 *		<rule/>
		 *	</dictionary>
		 * то $this->e()->{'rule'} вернёт не массив, а объект (!),
		 * но при этом @see count() для этого объекта работает как для массива (!),
		 * то есть реально возвращает количество детей типа rule.
		 * Далее, оператор [] также работает, как для массива (!)
		 * http://stackoverflow.com/a/16100099
		 * Класс \SimpleXMLElement — вообще один из самых необычных классов PHP.
		 */
		df_assert_eq(1, count($childNodes));
		$r = $childNodes[0];
		df_assert($r instanceof CX);
	}
	return $r;
}

/**
 * @used-by df_xml_child()
 * @param CX $e
 * @param string $name
 * @param bool $req [optional]
 * @return CX|null
 * @throws E
 */
function df_xml_children(CX $e, $name, $req = false) { /** @var CX|null $r */
	df_param_sne($name, 0);
	if (df_xml_exists_child($e, $name)) {
		/**
		 * Обратите внимание, что если мы имеем структуру:
		 *	<dictionary>
		 *		<rule/>
		 *		<rule/>
		 *		<rule/>
		 *	</dictionary>
		 * то $e->{'rule'} вернёт не массив, а объект (!),
		 * но при этом @see count() для этого объекта работает как для массива (!),
		 * то есть реально возвращает количество детей типа rule.
		 * Далее, оператор [] также работает, как для массива (!)
		 * http://stackoverflow.com/a/16100099
		 * Класс \SimpleXMLElement — вообще один из самых необычных классов PHP.
		 */
		$r = $e->{$name};
	}
	elseif (!$req) {
		$r = null;
	}
	else {
		df_error("Требуемый узел «{$name}» отсутствует в документе:\n{xml}", ['{xml}' => df_xml_report($e)]);
	}
	return $r;
}

/**
 * 2015-02-27
 * Алгоритм взят отсюда: http://stackoverflow.com/a/5344560
 * Проверил, что он работает: http://3v4l.org/tnEIJ
 * Обратите внимание, что isset() вместо empty() не сработает: http://3v4l.org/2P5o0
 * isset, однако, работает для проверки наличия дочерних листов: @see df_xml_exists_child()
 *
 * Обратите внимание, что оператор $e->{'тест'} всегда возвращает объект @see \SimpleXMLElement,
 * вне зависимости от наличия узла «тест», просто для отсутствующего узла данный объект будет пуст,
 * и empty() для него вернёт true.
 *
 * 2015-08-04
 * Заметил, что empty($e) для текстовых узлов всегда возвращает true,
 * даже если узел как строка приводится к true (например: «147»).
 * Например:
 * Например:
 *	<Остаток>
 *		<Склад>
 *			<Ид>6f87e83f-722c-11df-b336-0011955cba6b</Ид>
 *			<Количество>147</Количество>
 *		</Склад>
 *	</Остаток>
 * Если здесь сделать xpath Остаток/Склад/Количество,
 * то для узла «147» @see df_xml_exists($e) вернёт false.
 *
 * Обратите внимание, что эту особенность использует алгоритм @see df_check_leaf():
 * return !df_xml_exists($e) || !count($e->children());
 *
 * @used-by df_check_leaf()
 * @param CX|null $e
 * @return bool
 */
function df_xml_exists(CX $e = null) {return !empty($e);}

/**
 * http://stackoverflow.com/questions/1560827/php-simplexml-check-if-a-child-exist#comment20135428_1562158
 * @used-by df_xml_children()
 * @param CX $e
 * @param string $child
 * @return bool
 */
function df_xml_exists_child(CX $e, $child) {return isset($e->{$child});}

/**
 * 2016-08-31
 * @used-by \Df\API\Client::reqXml()
 * @used-by \Df\Framework\W\Result\Xml::__toString()
 * @used-by \Dfe\SecurePay\Refund::process()
 * @param string $tag
 * @param array(string => mixed) $contents
 * @param array(string => mixed) $p [optional]
 * @return string
 */
function df_xml_g($tag, array $contents, array $p = []) {return \Df\Xml\G::p($tag, $contents, $p);}

/**
 * 2016-09-01
 * 2018-12-18 Single quotes are not supported by some external systems (e.g., Vantiv), so now I use double quotes.
 * @see df_xml_parse_header()
 * @used-by \Df\Xml\G::_p()
 * @param string $enc [optional]
 * @param string $v [optional]
 * @return string
 */
function df_xml_header($enc = 'UTF-8', $v = '1.0') {return "<?xml version=\"$v\" encoding=\"$enc\"?>";}

/**
 * @used-by \Dfe\SecurePay\Refund::process()
 * @used-by \Dfe\Vantiv\Test\CaseT\Charge::t04()
 * @param string $tag
 * @param array(string => string) $attr [optional]
 * @param mixed[] $contents [optional]
 * @return X
 */
function df_xml_node($tag, array $attr = [], array $contents = []) {
	$r = df_xml_parse("<{$tag}/>"); /** @var X $r */
	$r->addAttributes($attr);
	$r->importArray($contents);
	return $r;
}

/**
 * @used-by df_xml_node()
 * @used-by df_xml_parse_a()
 * @used-by df_xml_prettify()
 * @used-by df_xml_x()
 * @used-by \Df\Xml\G::_p()
 * @used-by \Dfe\Robokassa\Api\Options::p()
 * @used-by \Dfe\SecurePay\Refund::process()
 * @param string|X $x
 * @param bool $throw [optional]
 * @return X|null
 * @throws E
 */
function df_xml_parse($x, $throw = true) {/** @var X $r */
	if ($x instanceof X) {
		$r = $x;
	}
	else {
		df_param_sne($x, 0);
		$r = null;
		try {$r = new X($x);}
		catch (\Exception $e) {
			if ($throw) {
				df_error(
					"При синтаксическом разборе документа XML произошёл сбой:\n"
					. "«%s»\n"
					. "********************\n"
					. "%s\n"
					. "********************\n"
					, df_ets($e)
					, df_trim($x)
				);
			}
		}
	}
	return $r;
}

/**
 * 2018-12-19
 * @uses \Magento\Framework\Simplexml\Element::asArray() returns XML tag's attributes
 * inside an `@` key, e.g:
 *	<authorizationResponse reportGroup="1272532" customerId="admin@mage2.pro">
 *		<litleTxnId>82924701437133501</litleTxnId>
 *		<orderId>f838868475</orderId>
 *		<response>000</response>
 *		<...>
 *	</authorizationResponse>
 * will be converted to:
 * 	{
 *		"@": {
 *			"customerId": "admin@mage2.pro",
 *			"reportGroup": "1272532"
 *		},
 *		"litleTxnId": "82924701437133501",
 *		"orderId": "f838868475",
 *		"response": "000",
 * 		<...>
 *	}
 * @used-by \Dfe\Vantiv\API\Client::_construct()
 * @param string|X $x
 * @return array(string => mixed)
 * @throws E
 */
function df_xml_parse_a($x) {return df_xml_parse($x)->asArray();}

/**
 * 2016-09-01
 * Если XML не отформатирован, то после его заголовка перенос строки идти не обязан: http://stackoverflow.com/a/8384602
 * @used-by df_xml_prettify()
 * @param string|X $x
 * @return string |null
 */
function df_xml_parse_header($x) {return df_preg_match('#^<\?xml.*\?>#', df_xml_s($x));}

/**
 * 2016-09-01
 * @used-by \Dfe\SecurePay\Refund::process()
 * @uses \Df\Xml\X::asNiceXml() не сохраняет заголовок XML.
 * @param string|X $x
 * @return string
 */
function df_xml_prettify($x) {return df_cc_n(df_xml_parse_header($x), df_xml_parse($x)->asNiceXml());}

/**
 * @used-by df_assert_leaf()
 * @used-by df_xml_children()
 * @param CX|MX|X $e
 * @return string
 */
function df_xml_report(CX $e) {return $e instanceof MX ? $e->asNiceXml() : $e->asXML();}

/**
 * 2016-09-01
 * @see df_xml_x()
 * @used-by df_xml_parse_header()
 * @param string|X $x
 * @return string
 */
function df_xml_s($x) {return is_string($x) ? $x : $x->asXML();}

/**
 * 2016-09-01
 * 2021-12-02 @deprecated It is unused.
 * @see df_xml_s()
 * @param string|X $x
 * @return X
 */
function df_xml_x($x) {return $x instanceof X ? $x : df_xml_parse($x);}