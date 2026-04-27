<?php

require_once __DIR__ . '/testframework.php';
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../modules/database.php';
require_once __DIR__ . '/../modules/page.php';

$testFramework = new TestFramework();

function testDbConnection() {
    global $config;
    $db = new Database($config["db"]["path"]);
    return assertExpression($db != null, "DB connected", "DB failed");
}

function testDbCount() {
    global $config;
    $db = new Database($config["db"]["path"]);
    return assertExpression($db->Count("page") >= 3);
}

function testDbCreate() {
    global $config;
    $db = new Database($config["db"]["path"]);

    $id = $db->Create("page", [
        "title" => "Test",
        "content" => "Test content"
    ]);

    return assertExpression($id > 0);
}

function testDbRead() {
    global $config;
    $db = new Database($config["db"]["path"]);

    $data = $db->Read("page", 1);
    return assertExpression($data['title'] === 'Page 1');
}

function testDbUpdate() {
    global $config;
    $db = new Database($config["db"]["path"]);

    $db->Update("page", 1, ["title" => "Updated"]);
    $data = $db->Read("page", 1);

    return assertExpression($data['title'] === 'Updated');
}

function testDbDelete() {
    global $config;
    $db = new Database($config["db"]["path"]);

    $id = $db->Create("page", ["title"=>"Del","content"=>"Del"]);
    $db->Delete("page", $id);

    $data = $db->Read("page", $id);
    return assertExpression($data === null);
}

function testPageRender() {
    $page = new Page(__DIR__ . '/../templates/index.tpl');

    $html = $page->Render([
        "title" => "Hello",
        "content" => "World"
    ]);

    return assertExpression(strpos($html, "Hello") !== false);
}

// добавление тестов
$testFramework->add('DB connection', 'testDbConnection');
$testFramework->add('DB count', 'testDbCount');
$testFramework->add('DB create', 'testDbCreate');
$testFramework->add('DB read', 'testDbRead');
$testFramework->add('DB update', 'testDbUpdate');
$testFramework->add('DB delete', 'testDbDelete');
$testFramework->add('Page render', 'testPageRender');

// запуск
$testFramework->run();
echo $testFramework->getResult();
