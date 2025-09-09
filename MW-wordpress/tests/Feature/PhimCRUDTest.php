<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\CRUD\PhimCRUD;
use App\Models\Phim;
use Illuminate\Http\Request;

class PhimCRUDTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test full CRUD flow using the PhimCRUD helper.
     * - store -> index -> show -> update -> destroy
     */
    public function test_full_crud_flow()
    {
        $crud = new PhimCRUD();

        // 1) CREATE (STORE)
        $storeReq = Request::create('/phim', 'POST', [
            'TenPhim' => 'Unit Test Film',
            'ThoiLuong' => 100,
            'NgayKhoiChieu' => '2024-12-01',
            'NuocSanXuat' => 'VN',
            'DinhDang' => '2D',
            'MoTa' => 'Mô tả test',
            'DaoDien' => 'Test Director',
            'DuongDanPoster' => 'poster_test.jpg',
        ]);

        $storeResp = $crud->store($storeReq);
        $this->assertEquals(201, $storeResp->getStatusCode(), 'Store should return 201');
        $payload = $storeResp->getData(true);
        $this->assertTrue($payload['success']);
        $this->assertArrayHasKey('data', $payload);

        // Ensure database contains the record
        $this->assertDatabaseHas('Phim', [
            'TenPhim' => 'Unit Test Film',
            'ThoiLuong' => 100,
        ]);

        // Get the created model
        $phim = Phim::where('TenPhim', 'Unit Test Film')->first();
        $this->assertNotNull($phim);

        // 2) INDEX
        $indexResp = $crud->index();
        $this->assertEquals(200, $indexResp->getStatusCode());
        $indexPayload = $indexResp->getData(true);
        $this->assertTrue($indexPayload['success']);
        $this->assertNotEmpty($indexPayload['data']);

        // 3) SHOW
        $showResp = $crud->show($phim->getKey());
        $this->assertEquals(200, $showResp->getStatusCode());
        $showPayload = $showResp->getData(true);
        $this->assertTrue($showPayload['success']);
        $this->assertEquals('Unit Test Film', data_get($showPayload, 'data.TenPhim'));

        // 4) UPDATE
        $updateReq = Request::create('/phim/' . $phim->getKey(), 'PUT', [
            'TenPhim' => 'Unit Test Film Updated',
            'ThoiLuong' => 110,
        ]);
        $updateResp = $crud->update($updateReq, $phim->getKey());
        $this->assertEquals(200, $updateResp->getStatusCode());
        $this->assertDatabaseHas('Phim', ['TenPhim' => 'Unit Test Film Updated', 'ThoiLuong' => 110]);

        // 5) DESTROY
        $destroyResp = $crud->destroy($phim->getKey());
        $this->assertEquals(200, $destroyResp->getStatusCode());
        $this->assertDatabaseMissing('Phim', ['TenPhim' => 'Unit Test Film Updated']);
    }
}
