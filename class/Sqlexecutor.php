<?php declare(strict_types=1);

namespace XoopsModules\Wgdeveloper;

/**
 * wgDeveloper module for xoops
 *
 * @copyright    2026 XOOPS Project (https://xoops.org)
 * @license      GPL 2.0 or later
 * @package      wgdeveloper
 * @author       Goffy - Wedega Webdesign Gabor - Email:webmaster@wedega.com - Website:https://xoops.wedega.com
 */


final class SqlExecutor
{
    /**
     * Execute an SQL statement and return the result.
     *
     * SELECT statements return column names and rows.
     * Other statements are executed using the XOOPS DB layer.
     *
     * @return array<string, mixed>
     */
    public static function execute(string $sql): array
    {
        $db = $GLOBALS['xoopsDB'];

        $sql = trim($sql);

        if ($sql === '') {
            return [
                'success' => false,
                'type'    => 'error',
                'message' => 'No SQL statement provided.',
            ];
        }

        // Skip leading whitespace and SQL comments.
        $statement = preg_replace(
            '~^(?:(?:\s+)|(?:--[^\r\n]*(?:\r?\n|$))|(?:\#[^\r\n]*(?:\r?\n|$))|(?:/\*.*?\*/))*~s',
            '',
            $sql
        );

        // Detect SELECT and common table expression queries.
        $isSelect = preg_match(
                '/^(SELECT|WITH)\b/i',
                (string) $statement
            ) === 1;

        try {
            if ($isSelect) {
                // Execute the SELECT query using the XOOPS DB layer.
                $result = $db->query($sql);

                if ($result === false) {
                    return self::dbError($db);
                }

                $columns = [];
                $rows = [];

                // Retrieve column metadata when supported by the driver.
                if (
                    is_object($result)
                    && method_exists($result, 'fetch_fields')
                ) {
                    foreach ($result->fetch_fields() as $field) {
                        $columns[] = $field->name;
                    }
                }

                // Fetch all result rows.
                while (($row = $db->fetchArray($result)) !== false) {
                    if (!is_array($row)) {
                        break;
                    }

                    // Fall back to array keys if metadata is unavailable.
                    if ($columns === []) {
                        $columns = array_keys($row);
                    }

                    $rows[] = $row;
                }

                return [
                    'success' => true,
                    'type'    => 'select',
                    'message' => sprintf(
                        '%d row(s) returned.',
                        count($rows)
                    ),
                    'columns' => $columns,
                    'rows'    => $rows,
                ];
            }

            // Execute all non-SELECT statements.
            $result = $db->exec($sql);

            if ($result === false) {
                return self::dbError($db);
            }

            // Retrieve the number of affected rows when supported.
            $affected = method_exists($db, 'getAffectedRows')
                ? $db->getAffectedRows()
                : null;

            return [
                'success'  => true,
                'type'     => 'write',
                'message'  => 'SQL statement executed successfully.',
                'affected' => $affected,
            ];

        } catch (\Throwable $e) {
            return [
                'success' => false,
                'type'    => 'error',
                'message' => $e->getMessage(),
                'code'    => 0,
            ];
        }
    }

    /**
     * Retrieve the last database error.
     *
     * @return array<string, mixed>
     */
    private static function dbError(object $db): array
    {
        $message = method_exists($db, 'error')
            ? (string) $db->error()
            : 'Unknown database error.';

        $code = 0;

        if (method_exists($db, 'errno')) {
            $code = (int) $db->errno();
        } elseif (method_exists($db, 'errorno')) {
            $code = (int) $db->errorno();
        }

        return [
            'success' => false,
            'type'    => 'error',
            'message' => $message,
            'code'    => $code,
        ];
    }
}
