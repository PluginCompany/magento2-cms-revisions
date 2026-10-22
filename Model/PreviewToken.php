<?php
/**
 * Copyright (c) 2014-2026 Plugin Company
 *
 * This file is part of CMS Revisions for Magento 2, released under the MIT License.
 * See the LICENSE file in the project root.
 */
namespace PluginCompany\CmsRevisions\Model;

use Magento\Framework\App\DeploymentConfig;

/**
 * Signed, expiring links for the front-end page revision preview.
 *
 * token = HMAC-SHA256(revision id + "|" + expiry timestamp), keyed with a key derived from the
 * installation's crypt key (app/etc/env.php crypt/key), so only the admin can create valid links.
 */
class PreviewToken
{
    /**
     * Lifetime of a preview link in seconds
     */
    const TTL = 3600;

    /**
     * @var DeploymentConfig
     */
    private $deploymentConfig;

    /**
     * @param DeploymentConfig $deploymentConfig
     */
    public function __construct(DeploymentConfig $deploymentConfig)
    {
        $this->deploymentConfig = $deploymentConfig;
    }

    /**
     * Create the URL parameters for previewing a page revision
     *
     * @param int|string $revisionId
     * @return array ['expires' => int, 'token' => string]
     */
    public function create($revisionId)
    {
        $expires = time() + self::TTL;
        return [
            'expires' => $expires,
            'token' => $this->sign($revisionId, $expires)
        ];
    }

    /**
     * Check a preview link: valid signature and not expired
     *
     * @param mixed $revisionId
     * @param mixed $expires
     * @param mixed $token
     * @return bool
     */
    public function isValid($revisionId, $expires, $token)
    {
        if (!is_string($token) || !preg_match('/^[0-9a-f]{64}$/', $token)) {
            return false;
        }
        if (!is_scalar($revisionId) || !ctype_digit((string)$revisionId)) {
            return false;
        }
        if (!is_scalar($expires) || !ctype_digit((string)$expires)) {
            return false;
        }
        $expires = (int)$expires;
        // not expired, and not further in the future than a fresh link (60 s clock tolerance)
        if ($expires < time() || $expires > time() + self::TTL + 60) {
            return false;
        }
        $expected = $this->sign($revisionId, $expires);
        return $expected !== '' && hash_equals($expected, $token);
    }

    /**
     * @param int|string $revisionId
     * @param int $expires
     * @return string hex HMAC, or '' when no crypt key is configured
     */
    private function sign($revisionId, $expires)
    {
        $key = $this->getKey();
        if ($key === '') {
            return '';
        }
        return hash_hmac('sha256', (string)(int)$revisionId . '|' . (int)$expires, $key);
    }

    /**
     * @return string
     */
    private function getKey()
    {
        $cryptKey = (string)$this->deploymentConfig->get('crypt/key');
        if (trim($cryptKey) === '') {
            return '';
        }
        return hash_hmac('sha256', 'PluginCompany_CmsRevisions page revision preview', $cryptKey);
    }
}
