<?php

namespace App\Remotes\Paspor;

use App\Remotes\Paspor;
use GuzzleHttp\Exception\GuzzleException;

class User extends Paspor
{
    protected $object = 'user';

    /**
     * @param string $pasporId
     * @param string $passwd
     * @param null $isEmail
     * @return mixed
     * @throws GuzzleException
     */
    public function password($pasporId, $passwd, $isEmail = null)
    {
        $params = [
            'userid' => $pasporId,
            'passwd' => md5($passwd),
        ];

        if ($isEmail !== null) {
            $params['is_email'] = $isEmail;
        }

        return $this->request('update_user', $params);
    }

    /**
     * @param $users
     * @param $layanan
     * @param $adminId
     * @return mixed
     * @throws GuzzleException
     */
    public function add($users, $layanan, $adminId)
    {
        $data = [
            'users'      => $users,
            'k_layanans' => $layanan,
            'is_notif'   => 0,
            'silent'     => 1,
            'adminid'    => $adminId,
        ];

        return $this->request('addusers', $data);
    }

    /**
     * @param string $email
     * @return mixed
     * @throws GuzzleException
     */
    public function getUserByEmail($email)
    {
        return $this->request('getuser_by_email', [
            'email' => $email,
        ]);
    }

    /**
     * @param int[] $ids
     * @return mixed
     * @throws GuzzleException
     */
    public function listUserByIds($ids)
    {
        return $this->request('list_users_by_ids', [
            'userids' => $ids,
        ]);
    }

    /**
     * @param string[] $emails
     * @return mixed
     * @throws GuzzleException
     */
    public function listUserByEmails($emails)
    {
        return $this->request('list_users_by_emails', [
            'emails' => $emails,
        ]);
    }

    /**
     * @param int $userId
     * @param int|int[] $layananIds
     * @param int $adminId
     * @return mixed
     * @throws GuzzleException
     */
    public function addUserLayan($userId, $layananIds, $adminId)
    {
        if (!is_array($layananIds)) {
            $layananIds = [$layananIds];
        }

        return $this->request('add_user_layan', [
            'userid'  => $userId,
            'layanan' => $layananIds,
            'adminid' => $adminId,
        ]);
    }

    /**
     * @param int $userId
     * @param int $layananId
     * @return mixed
     * @throws GuzzleException
     */
    public function delUserLayan($userId, $layananId)
    {
        return $this->request('del_user_layan', [
            'userid'     => $userId,
            'layanan_id' => $layananId,
        ]);
    }

    /**
     * @param string $pasporId
     * @param array $params
     * @return mixed
     * @throws GuzzleException
     */
    public function update($pasporId, array $params)
    {
        $params['userid'] = $pasporId;
        return $this->request('update_user', $params);
    }

    /**
     * @throws GuzzleException
     */
    public function addUsersWithSosial(array $users)
    {
        $this->object     = null;
        $this->httpMethod = 'POST';
        try {
            $params = $users;
            return $this->request('v2/user/usersosials?' . http_build_query(['k_jenis_sosial' => 4]), $params);
        } finally {
            $this->object     = 'user';
            $this->httpMethod = 'GET';
        }
    }

    /**
     * @throws GuzzleException
     */
    public function findUserSosial(int $pasporId, int $kJenisSosial)
    {
        $this->object = null;
        try {
            $params['k_jenis_sosial'] = $kJenisSosial;
            return $this->request('v2/user/' . $pasporId . '/sosial', $params);
        } finally {
            $this->object = 'user';
        }
    }

    /**
     * @throws GuzzleException
     */
    public function addAkunSosial(int $pasporId, array $params)
    {
        $this->object     = null;
        $this->httpMethod = 'POST';
        try {
            $params = [
                'sosialid'       => $params['sosialid'],
                'email'          => $params['email'],
                'k_jenis_sosial' => $params['k_jenis_sosial'],
            ];

            return $this->request('v2/user/' . $pasporId . '/sosial', $params);
        } finally {
            $this->object     = 'user';
            $this->httpMethod = 'GET';
        }
    }
}
