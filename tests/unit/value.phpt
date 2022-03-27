<?php

use \Tester\Assert;
use \Smuuf\Primi\Structures\{
	Value,
	StringValue,
	NumberValue,
	RegexValue,
	DictValue,
	ListValue,
	FuncValue,
	BoolValue
};

require __DIR__ . '/../bootstrap.php';

Assert::same(NumberValue::TYPE, Value::buildAutomatic(1)::TYPE);
Assert::same(NumberValue::TYPE, Value::buildAutomatic(-1)::TYPE);
Assert::same(NumberValue::TYPE, Value::buildAutomatic("0")::TYPE);
Assert::same(NumberValue::TYPE, Value::buildAutomatic("+4")::TYPE);
Assert::same(NumberValue::TYPE, Value::buildAutomatic(-123)::TYPE);

Assert::same(StringValue::TYPE, Value::buildAutomatic("a")::TYPE);
Assert::same(StringValue::TYPE, Value::buildAutomatic("")::TYPE);
Assert::same(StringValue::TYPE, Value::buildAutomatic("word")::TYPE);
Assert::same(StringValue::TYPE, Value::buildAutomatic("-1 squirrels")::TYPE);

Assert::same(BoolValue::TYPE, Value::buildAutomatic(true)::TYPE);
Assert::same(BoolValue::TYPE, Value::buildAutomatic(false)::TYPE);

Assert::same(ListValue::TYPE, Value::buildAutomatic([])::TYPE);
Assert::same(ListValue::TYPE, Value::buildAutomatic([1])::TYPE);

Assert::same(DictValue::TYPE, Value::buildAutomatic([4 => 'a', 5 => 'b'])::TYPE);
Assert::same(DictValue::TYPE, Value::buildAutomatic(['a' => 'x', 'y' => 'z'])::TYPE);

Assert::same(FuncValue::TYPE, Value::buildAutomatic(function() {})::TYPE);
Assert::same(FuncValue::TYPE, Value::buildAutomatic(function(NumberValue $x, DictValue $y) { return 1; })::TYPE);

//
// Getting string representation of values.
//

// String - 'repr' will have double quotes around it.
$v = new StringValue("hel\"lo!");
Assert::same('"hel\"lo!"', $v->getStringRepr());

// String - 'value' will NOT have double quotes around it.
$v = new StringValue("hel\"lo!");
Assert::same('hel"lo!', $v->getStringValue());

// Number.
$v = new NumberValue("123");
Assert::same('123', $v->getStringRepr());
// Number which floats.
$v = new NumberValue("123.789");
Assert::same('123.789', $v->getStringRepr());
// Number which floats 2.
$v = new NumberValue("000123.789");
Assert::same('123.789', $v->getStringRepr());

// Bool: True.
$v = new BoolValue(true);
Assert::same('true', $v->getStringRepr());
// Bool: False
$v = new BoolValue(false);
Assert::same('false', $v->getStringRepr());

// Regex 1.
$v = new RegexValue('/abc/');
Assert::same('r"/abc/"', $v->getStringRepr());
// Regex 2.
$v = new RegexValue('abc');
Assert::same('r"abc"', $v->getStringRepr());

// List.
$v = new ListValue([
	Value::buildAutomatic(1),
	Value::buildAutomatic("xxx"),
	Value::buildAutomatic(false),
	new RegexValue('abc'),
	new RegexValue('/abc/'),
]);
Assert::same('[1, "xxx", false, r"abc", r"/abc/"]', $v->getStringRepr());

// Dict.
$v = new DictValue([
	'aaa' => Value::buildAutomatic(1),
	'bbb' => Value::buildAutomatic("xxx"),
	'ccc' => Value::buildAutomatic(false),
	'ddd' => new RegexValue('abc'),
	'___' => new RegexValue('/abc/'),
]);
Assert::same('{"aaa": 1, "bbb": "xxx", "ccc": false, "ddd": r"abc", "___": r"/abc/"}', $v->getStringRepr());

