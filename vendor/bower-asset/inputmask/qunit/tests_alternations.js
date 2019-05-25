export default function (qunit, $, Inputmask) {
	qunit.module("Alternations");

	qunit.test("\"9{1,2}C|S A{1,3} 9{4}\" - ankitajain32", function (assert) {
		var $fixture = $("#qunit-fixture");
		$fixture.append('<input type="text" id="testmask" />');
		var testmask = document.getElementById("testmask");

		Inputmask("9{1,2}C|S A{1,3} 9{4}").mask(testmask);
		$("#testmask").Type("12Cabc1234");
		assert.equal(testmask.inputmask._valueGet(), "12C ABC 1234", "Result " + testmask.inputmask._valueGet());
	});

	qunit.test("\"9{1,2}C|S A{1,3} 9{4}\" replace C with S - ankitajain32", function (assert) {
		var $fixture = $("#qunit-fixture");
		$fixture.append('<input type="text" id="testmask" />');
		var testmask = document.getElementById("testmask");

		Inputmask("9{1,2}C|S A{1,3} 9{4}").mask(testmask);
		$("#testmask").Type("12Cabc1234");
		$.caret(testmask, 2, 3);
		$("#testmask").Type("S");
		assert.equal(testmask.inputmask._valueGet(), "12S ABC 1234", "Result " + testmask.inputmask._valueGet());
	});

	qunit.test("nested alternations 1", function (assert) {
		var $fixture = $("#qunit-fixture");
		$fixture.append('<input type="text" id="testmask" />');
		var testmask = document.getElementById("testmask");

		Inputmask("0<2)##-##-##>|<3<4)#-##-##>|<5)#-##-##>|<6)#-##-##>>", {
			groupmarker: {
				start: "<",
				end: ">"
			}
		}).mask(testmask);

		$("#testmask").Type("02121212");

		assert.equal(testmask.inputmask._valueGet(), "02)12-12-12", "Result " + testmask.inputmask._valueGet());
	});

	qunit.test("nested alternations 2", function (assert) {
		var $fixture = $("#qunit-fixture");
		$fixture.append('<input type="text" id="testmask" />');
		var testmask = document.getElementById("testmask");

		Inputmask("0<2)##-##-##>|<3<4)#-##-##>|<5)#-##-##>|<6)#-##-##>>", {
			groupmarker: {
				start: "<",
				end: ">"
			}
		}).mask(testmask);

		$("#testmask").Type("03411212");

		assert.equal(testmask.inputmask._valueGet(), "034)1-12-12", "Result " + testmask.inputmask._valueGet());
	});

	qunit.test("nested alternations 3", function (assert) {
		var $fixture = $("#qunit-fixture");
		$fixture.append('<input type="text" id="testmask" />');
		var testmask = document.getElementById("testmask");

		Inputmask("0<2)##-##-##>|<3<4)#-##-##>|<5)#-##-##>|<6)#-##-##>>", {
			groupmarker: {
				start: "<",
				end: ">"
			}
		}).mask(testmask);

		$("#testmask").Type("03511212");

		assert.equal(testmask.inputmask._valueGet(), "035)1-12-12", "Result " + testmask.inputmask._valueGet());
	});

	qunit.test("nested alternations 4", function (assert) {
		var $fixture = $("#qunit-fixture");
		$fixture.append('<input type="text" id="testmask" />');
		var testmask = document.getElementById("testmask");

		Inputmask("0<2)##-##-##>|<3<4)#-##-##>|<5)#-##-##>|<6)#-##-##>>", {
			groupmarker: {
				start: "<",
				end: ">"
			}
		}).mask(testmask);

		$("#testmask").Type("03611212");

		assert.equal(testmask.inputmask._valueGet(), "036)1-12-12", "Result " + testmask.inputmask._valueGet());
	});

	qunit.test("alternations W|XY|Z", function (assert) {
		var $fixture = $("#qunit-fixture");
		$fixture.append('<input type="text" id="testmask" />');
		var testmask = document.getElementById("testmask");

		Inputmask("W|XY|Z").mask(testmask);

		$("#testmask").Type("WZ");

		assert.equal(testmask.inputmask._valueGet(), "WZ", "Result " + testmask.inputmask._valueGet());
	});

	qunit.test("alternations (W)|(X)(Y)|(Z)", function (assert) {
		var $fixture = $("#qunit-fixture");
		$fixture.append('<input type="text" id="testmask" />');
		var testmask = document.getElementById("testmask");

		Inputmask("(W)|(X)(Y)|(Z)").mask(testmask);

		$("#testmask").Type("WZ");

		assert.equal(testmask.inputmask._valueGet(), "WZ", "Result " + testmask.inputmask._valueGet());
	});

	qunit.test("alternations (9{1,3}|SE|NE|SW|NW)-9{1,3} - yesman85", function (assert) {
		var $fixture = $("#qunit-fixture");
		$fixture.append('<input type="text" id="testmask" />');
		var testmask = document.getElementById("testmask");

		Inputmask("(9{1,3}|SE|NE|SW|NW)-9{1,3}").mask(testmask);

		$("#testmask").Type("(NE123");

		assert.equal(testmask.inputmask._valueGet(), "NE-123", "Result " + testmask.inputmask._valueGet());
	});

	qunit.test("((S))", function (assert) {
		var $fixture = $("#qunit-fixture");
		$fixture.append('<input type="text" id="testmask" />');
		var testmask = document.getElementById("testmask");

		Inputmask("((S))").mask(testmask);
		testmask.focus();
		assert.equal(testmask.inputmask._valueGet(), "((S))", "Result " + testmask.inputmask._valueGet());
	});
	qunit.test("((S)", function (assert) {
		var $fixture = $("#qunit-fixture");
		$fixture.append('<input type="text" id="testmask" />');
		var testmask = document.getElementById("testmask");

		Inputmask("((S)").mask(testmask);
		testmask.focus();
		assert.equal(testmask.inputmask._valueGet(), "((S)", "Result " + testmask.inputmask._valueGet());
	});

	qunit.test("+371-99-999-999 - artemkaint", function (assert) {
		var $fixture = $("#qunit-fixture");
		$fixture.append('<input type="text" id="testmask" />');
		var testmask = document.getElementById("testmask");

		Inputmask([
			"+371-99-999-999",
			"+370(999)99-999",
			"+375(99)999-99-99",
			"+374-99-999-999",
			"+380(99)999-99-99",
			"+358(999)999-99-99",
			"+373-9999-9999",
			"+381-99-999-9999"
		]).mask(testmask);
		testmask.focus();
		$("#testmask").Type("112123123");
		assert.equal(testmask.inputmask._valueGet(), "+371-12-123-123", "Result " + testmask.inputmask._valueGet());
	});
	qunit.test("+371-99-999-999 - artemkaint", function (assert) {
		var $fixture = $("#qunit-fixture");
		$fixture.append('<input type="text" id="testmask" />');
		var testmask = document.getElementById("testmask");

		Inputmask([
			"+371-99-999-999",
			"+370(999)99-999",
			"+375(99)999-99-99",
			"+374-99-999-999",
			"+380(99)999-99-99",
			"+358(999)999-99-99",
			"+373-9999-9999",
			"+381-99-999-9999"
		]).mask(testmask);
		testmask.focus();
		$("#testmask").Type("412123123");
		assert.equal(testmask.inputmask._valueGet(), "+374-12-123-123", "Result " + testmask.inputmask._valueGet());
	});

	qunit.test("(9)|(a9) - type 1 - ivaninDarpatov", function (assert) {
		var $fixture = $("#qunit-fixture");
		$fixture.append('<input type="text" id="testmask" />');
		var testmask = document.getElementById("testmask");

		Inputmask("(9)|(a9)").mask(testmask);
		testmask.focus();
		$("#testmask").Type("12");
		assert.equal(testmask.inputmask._valueGet(), "1", "Result " + testmask.inputmask._valueGet());
	});

	qunit.test("(9)|(a9) - type a1 - ivaninDarpatov", function (assert) {
		var $fixture = $("#qunit-fixture");
		$fixture.append('<input type="text" id="testmask" />');
		var testmask = document.getElementById("testmask");

		Inputmask("(9)|(a9)").mask(testmask);
		testmask.focus();
		$("#testmask").Type("a1");
		assert.equal(testmask.inputmask._valueGet(), "a1", "Result " + testmask.inputmask._valueGet());
	});

	qunit.test("(999)|(0aa) - type 0ab - ivaninDarpatov", function (assert) {
		var $fixture = $("#qunit-fixture");
		$fixture.append('<input type="text" id="testmask" />');
		var testmask = document.getElementById("testmask");

		Inputmask("(999)|(0aa)").mask(testmask);
		testmask.focus();
		$("#testmask").Type("0ab");
		assert.equal(testmask.inputmask._valueGet(), "0ab", "Result " + testmask.inputmask._valueGet());
	});

	qunit.test("(999)|(0aa) - type 1ab - ivaninDarpatov", function (assert) {
		var $fixture = $("#qunit-fixture");
		$fixture.append('<input type="text" id="testmask" />');
		var testmask = document.getElementById("testmask");

		Inputmask("(999)|(0aa)").mask(testmask);
		testmask.focus();
		$("#testmask").Type("1ab");
		assert.equal(testmask.inputmask._valueGet(), "1__", "Result " + testmask.inputmask._valueGet());
	});

	qunit.test("(9)|(09)|(19)|(2f) - type 41 - ivaninDarpatov", function (assert) {
		var $fixture = $("#qunit-fixture");
		$fixture.append('<input type="text" id="testmask" />');
		var testmask = document.getElementById("testmask");

		Inputmask("(9)|(09)|(19)|(2f)", {
			definitions: {
				"f": {validator: "[0-3]"}
			}
		}).mask(testmask);
		testmask.focus();
		$("#testmask").Type("41");
		assert.equal(testmask.inputmask._valueGet(), "4", "Result " + testmask.inputmask._valueGet());
	});

	qunit.test("(9)|(09)|(19)|(2f) - type 01 - ivaninDarpatov", function (assert) {
		var $fixture = $("#qunit-fixture");
		$fixture.append('<input type="text" id="testmask" />');
		var testmask = document.getElementById("testmask");

		Inputmask("(9)|(09)|(19)|(2f)", {
			definitions: {
				"f": {validator: "[0-3]"}
			}
		}).mask(testmask);
		testmask.focus();
		$("#testmask").Type("01");
		assert.equal(testmask.inputmask._valueGet(), "01", "Result " + testmask.inputmask._valueGet());
	});
	qunit.test("(9)|(09)|(19)|(2f) - type 11 - ivaninDarpatov", function (assert) {
		var $fixture = $("#qunit-fixture");
		$fixture.append('<input type="text" id="testmask" />');
		var testmask = document.getElementById("testmask");

		Inputmask("(9)|(09)|(19)|(2f)", {
			definitions: {
				"f": {validator: "[0-3]"}
			}
		}).mask(testmask);
		testmask.focus();
		$("#testmask").Type("11");
		assert.equal(testmask.inputmask._valueGet(), "11", "Result " + testmask.inputmask._valueGet());
	});

	qunit.test("(9)|(09)|(19)|(2f) - type 23 - ivaninDarpatov", function (assert) {
		var $fixture = $("#qunit-fixture");
		$fixture.append('<input type="text" id="testmask" />');
		var testmask = document.getElementById("testmask");

		Inputmask("(9)|(09)|(19)|(2f)", {
			definitions: {
				"f": {validator: "[0-3]"}
			}
		}).mask(testmask);
		testmask.focus();
		$("#testmask").Type("23");
		assert.equal(testmask.inputmask._valueGet(), "23", "Result " + testmask.inputmask._valueGet());
	});

	qunit.test("(9|09|19|2f) - type 24 - ivaninDarpatov", function (assert) {
		var $fixture = $("#qunit-fixture");
		$fixture.append('<input type="text" id="testmask" />');
		var testmask = document.getElementById("testmask");

		Inputmask("(9|09|19|2f)", {
			definitions: {
				"f": {validator: "[0-3]"}
			}
		}).mask(testmask);
		testmask.focus();
		$("#testmask").Type("24");
		assert.equal(testmask.inputmask._valueGet(), "2_", "Result " + testmask.inputmask._valueGet());
	});
};
