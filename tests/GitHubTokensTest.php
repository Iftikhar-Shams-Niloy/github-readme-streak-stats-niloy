<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

require_once dirname(__DIR__) . "/vendor/autoload.php";
require_once "api/stats.php";

final class GitHubTokensTest extends TestCase
{
    private string|false $originalTokenEnv;
    private string|false $originalToken2Env;
    private mixed $originalEnvToken;
    private mixed $originalEnvToken2;
    private mixed $originalServerToken;
    private mixed $originalServerToken2;

    protected function setUp(): void
    {
        $this->originalTokenEnv = getenv("TOKEN");
        $this->originalToken2Env = getenv("TOKEN2");
        $this->originalEnvToken = $_ENV["TOKEN"] ?? null;
        $this->originalEnvToken2 = $_ENV["TOKEN2"] ?? null;
        $this->originalServerToken = $_SERVER["TOKEN"] ?? null;
        $this->originalServerToken2 = $_SERVER["TOKEN2"] ?? null;

        unset($GLOBALS["ALL_TOKENS"], $_ENV["TOKEN"], $_ENV["TOKEN2"], $_SERVER["TOKEN"], $_SERVER["TOKEN2"]);
        putenv("TOKEN");
        putenv("TOKEN2");
    }

    protected function tearDown(): void
    {
        unset($GLOBALS["ALL_TOKENS"], $_ENV["TOKEN"], $_ENV["TOKEN2"], $_SERVER["TOKEN"], $_SERVER["TOKEN2"]);

        if ($this->originalTokenEnv === false) {
            putenv("TOKEN");
        } else {
            putenv("TOKEN={$this->originalTokenEnv}");
        }

        if ($this->originalToken2Env === false) {
            putenv("TOKEN2");
        } else {
            putenv("TOKEN2={$this->originalToken2Env}");
        }

        if ($this->originalEnvToken !== null) {
            $_ENV["TOKEN"] = $this->originalEnvToken;
        }
        if ($this->originalEnvToken2 !== null) {
            $_ENV["TOKEN2"] = $this->originalEnvToken2;
        }
        if ($this->originalServerToken !== null) {
            $_SERVER["TOKEN"] = $this->originalServerToken;
        }
        if ($this->originalServerToken2 !== null) {
            $_SERVER["TOKEN2"] = $this->originalServerToken2;
        }
    }

    public function testReadsTokensFromGetenvWhenSuperglobalsAreEmpty(): void
    {
        putenv("TOKEN=token-one");
        putenv("TOKEN2=token-two");

        $this->assertSame(["token-one", "token-two"], getGitHubTokens());
    }
}
