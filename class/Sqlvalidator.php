<?php declare(strict_types=1);

namespace XoopsModules\Wgdeveloper;

/**
 * XOOPS SQL Validator
 *
 * Validates SQL syntax using the existing XOOPS database connection.
 *
 * Requirements:
 * - XOOPS 2.7.x
 * - MariaDB / MySQL with PREPARE support
 * - $GLOBALS['xoopsDB'] must be available
 *
 * IMPORTANT:
 * The SQL statement being validated is not executed.
 *
 * * wgDeveloper module for xoops
 * *
 * * @copyright    2026 XOOPS Project (https://xoops.org)
 * * @license      GPL 2.0 or later
 * * @package      wgdeveloper
 * * @author       Goffy - Wedega Webdesign Gabor - Email:webmaster@wedega.com - Website:https://xoops.wedega.com
 *
*/
final class SqlValidator
{
    /** @var string The last error message. */
    private static string $lastError = '';

    /** @var int The last database error number. */
    private static int $lastErrorNumber = 0;

    /** @var string|null The last SQLSTATE value. */
    private static ?string $lastSqlState = null;

    /**
     * Checks an SQL statement for syntax errors.
     *
     * @param string $sql The SQL statement to validate.
     * @return bool True if validation succeeds, false otherwise.
     */
    public static function check(string $sql): bool
    {
        self::reset();

        $sql = trim($sql);

        if ($sql === '') {
            self::$lastError = 'The SQL statement is empty.';
            return false;
        }

        if (!isset($GLOBALS['xoopsDB'])) {
            self::$lastError = 'The XOOPS database connection is not available.';
            return false;
        }

        $db = $GLOBALS['xoopsDB'];

        /**
         * PREPARE expects the SQL statement as a string literal.
         * Therefore, backslashes and single quotes must be escaped.
         */
        $preparedSql = self::escapeSqlString($sql);

        /**
         * Use a unique statement name.
         * Prepared statements are scoped to the current connection.
         */
        $statementName = 'xoops_sql_validator';

        /**
         * Attempt to remove any existing prepared statement
         * with the same name before creating a new one.
         */
        self::deallocate($db, $statementName);

        /**
         * PREPARE parses the SQL statement but does not execute
         * the underlying SELECT, INSERT, UPDATE, or DELETE.
         */
        $prepareSql = "PREPARE {$statementName} FROM '{$preparedSql}'";

        $result = self::query($db, $prepareSql);

        if ($result === false) {
            self::readError($db);

            /** Clean up the prepared statement if necessary. */
            self::deallocate($db, $statementName);

            return false;
        }

        /** Release the prepared statement after successful validation. */
        self::deallocate($db, $statementName);

        return true;
    }

    /**
     * Validates SQL and returns detailed information.
     *
     * @param string $sql The SQL statement to validate.
     *
     * @return array{
     *     valid: bool,
     *     message: string,
     *     error: string,
     *     errorNumber: int,
     *     sqlState: string|null
     * }
     */
    public static function validate(string $sql): array
    {
        $valid = self::check($sql);

        return [
            'valid'       => $valid,
            'message'     => $valid
                ? 'SQL syntax is valid.'
                : self::$lastError,
            'error'       => self::$lastError,
            'errorNumber' => self::$lastErrorNumber,
            'sqlState'    => self::$lastSqlState,
        ];
    }

    /**
     * Returns the last error message.
     */
    public static function getError(): string
    {
        return self::$lastError;
    }

    /**
     * Returns the last MariaDB/MySQL error number.
     */
    public static function getErrorNumber(): int
    {
        return self::$lastErrorNumber;
    }

    /**
     * Returns the last SQLSTATE value.
     */
    public static function getSqlState(): ?string
    {
        return self::$lastSqlState;
    }

    /**
     * Resets the internal error state.
     */
    private static function reset(): void
    {
        self::$lastError = '';
        self::$lastErrorNumber = 0;
        self::$lastSqlState = null;
    }

    /**
     * Executes PREPARE or DEALLOCATE statements through XOOPS.
     *
     * query() is used because PREPARE is not a regular
     * INSERT, UPDATE, or DELETE statement.
     */
    private static function query(object $db, string $sql): mixed
    {
        try {
            return $db->query($sql);
        } catch (\Throwable $e) {
            self::$lastError = $e->getMessage();

            return false;
        }
    }

    /**
     * Releases a prepared statement if it exists.
     *
     * Cleanup errors are intentionally ignored because they
     * should not be treated as syntax errors in the original SQL.
     */
    private static function deallocate(object $db, string $statementName): void
    {
        try {
            $db->query(
                "DEALLOCATE PREPARE {$statementName}"
            );
        } catch (\Throwable) {
            // Ignore cleanup errors.
        }
    }

    /**
     * Reads the error information from the XOOPS database layer.
     */
    private static function readError(object $db): void
    {
        try {
            if (method_exists($db, 'error')) {
                self::$lastError = (string) $db->error();
            }

            if (method_exists($db, 'errno')) {
                self::$lastErrorNumber = (int) $db->errno();
            }

            /**
             * Some implementations include SQLSTATE in the error
             * message. If it is not present, the value remains null.
             */
            self::$lastSqlState = self::extractSqlState(
                self::$lastError
            );

            if (self::$lastError === '') {
                self::$lastError = 'Unknown database error.';
            }
        } catch (\Throwable $e) {
            self::$lastError = $e->getMessage();
        }
    }

    /**
     * Extracts an SQLSTATE value from an error message.
     *
     * Example: SQLSTATE[42000]
     */
    private static function extractSqlState(string $message): ?string
    {
        if (preg_match(
            '/SQLSTATE\[(\w{5})\]/i',
            $message,
            $matches
        )) {
            return strtoupper($matches[1]);
        }

        return null;
    }

    /**
     * Escapes a PHP string for use as a MariaDB/MySQL string literal.
     *
     * addslashes() is not sufficient because the database server's
     * SQL string escaping rules must be taken into account.
     */
    private static function escapeSqlString(string $sql): string
    {
        /**
         * Within a string literal such as:
         *
         * PREPARE stmt FROM '...';
         *
         * backslashes and single quotes must be escaped.
         */
        return str_replace(
            [
                '\\',
                "'",
            ],
            [
                '\\\\',
                "\\'",
            ],
            $sql
        );
    }
}
