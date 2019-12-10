<?php

namespace Tests\Feature;

use App\Critical;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CriticalTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * 存储
     */
    public function testCriticalsStoreTest()
    {
        $user = factory(User::class)->create();
        $user->assignRole('标记批判性思维');
        $data = [
            'text' => '我是文字',
            'analysis' => '我是分析',
            'images' => [],
            'start' => time() * 1000,
        ];
        $response = $this->actingAs($user)->json('post', '/criticals', $data);
        $response->assertStatus(200);
    }

    /**
     * 查询
     */
    public function testCriticalsIndexTest()
    {
        $user1 = factory(User::class)->create();
        $user1->assignRole('标记批判性思维');
        $user2 = factory(User::class)->create();
        $user2->assignRole('标记批判性思维');
        $admin = factory(User::class)->create();
        $admin->assignRole('管理员');

        $critical = new Critical();
        $critical->text = '我是文字';
        $critical->images = '[]';
        $critical->analysis = '我是分析';
        $critical->user_id = $user1->id;
        $critical->cost = 100;
        $critical->save();

        $response = $this->actingAs($user1)->json('get', '/criticals');
        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertSeeText($user1->name);

        $response = $this->actingAs($user2)->json('get', '/criticals');
        $response->assertStatus(200)
            ->assertJsonCount(0, 'data')
            ->assertDontSeeText($user1->name);

        $response = $this->actingAs($user1)->json('get', '/criticals');
        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertSeeText($user1->name);
    }

    /**
     * 修改
     */
    public function testCriticalsUpdateTest()
    {
        $user1 = factory(User::class)->create();
        $user1->assignRole('标记批判性思维');
        $user2 = factory(User::class)->create();
        $user2->assignRole('标记批判性思维');
        $admin = factory(User::class)->create();
        $admin->assignRole('管理员');

        $critical = new Critical();
        $critical->text = '我是文字';
        $critical->images = '[]';
        $critical->analysis = '我是分析';
        $critical->user_id = $user1->id;
        $critical->cost = 100;
        $critical->save();

        $data = [
            'text' => '用户修改后',
            'analysis' => '我是分析',
            'start' => time() * 1000,
        ];

        $response = $this->actingAs($user2)->json('put', '/criticals/' . $critical->id, $data);
        $response->assertStatus(403);
        $response = $this->actingAs($user1)->json('put', '/criticals/' . $critical->id, $data);
        $response->assertStatus(200);
        $critical = Critical::find($critical->id);
        self::assertEquals($data['text'], $critical->text);
        $data['text'] = '管理员修改后';
        $response = $this->actingAs($admin)->json('put', '/criticals/' . $critical->id, $data);
        $response->assertStatus(200);
        $critical = Critical::find($critical->id);
        self::assertEquals($data['text'], $critical->text);
    }

    /**
     * 删除
     */
    public function testCriticalsDestroyTest()
    {
        $user1 = factory(User::class)->create();
        $user1->assignRole('标记批判性思维');
        $user2 = factory(User::class)->create();
        $user2->assignRole('标记批判性思维');
        $admin = factory(User::class)->create();
        $admin->assignRole('管理员');

        $critical = new Critical();
        $critical->text = '我是文字';
        $critical->images = '[]';
        $critical->analysis = '我是分析';
        $critical->user_id = $user1->id;
        $critical->cost = 100;
        $critical->save();

        $response = $this->actingAs($user2)->json('delete', '/criticals/' . $critical->id);
        $response->assertStatus(403);

        $response = $this->actingAs($user1)->json('delete', '/criticals/' . $critical->id);
        $response->assertStatus(200);
        $critical = Critical::find($critical->id);
        assert(is_null($critical));

        $critical = new Critical();
        $critical->text = '我是文字';
        $critical->images = '[]';
        $critical->analysis = '我是分析';
        $critical->user_id = $user1->id;
        $critical->cost = 100;
        $critical->save();
        $response = $this->actingAs($admin)->json('delete', '/criticals/' . $critical->id);
        $response->assertStatus(200);
        $critical = Critical::find($critical->id);
        assert(is_null($critical));
    }
}