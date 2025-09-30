<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <title>Admin - Nhân Viên</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="p-4">
  <div class="container">
    <h2>Quản lý Nhân Viên</h2>

    <!-- Form thêm / sửa nhân viên (gõ mã bằng tay) -->
    <form id="nhanvien-form" class="mb-4">
      <div class="mb-3">
        <label for="MaNguoiDung" class="form-label">Mã Người Dùng (nhập bằng tay)</label>
        <input type="text" class="form-control" id="MaNguoiDung" name="MaNguoiDung" required>
        <div class="form-text">Nhập mã chính xác như trong bảng Người Dùng.</div>
      </div>

      <div class="mb-3">
        <label for="ChucVu" class="form-label">Chức Vụ</label>
        <input type="text" class="form-control" id="ChucVu" name="ChucVu">
      </div>

      <div class="mb-3">
        <label for="Luong" class="form-label">Lương</label>
        <input type="number" step="0.01" class="form-control" id="Luong" name="Luong">
      </div>

      <div class="mb-3">
        <label for="VaiTro" class="form-label">Vai Trò</label>
        <input type="text" class="form-control" id="VaiTro" name="VaiTro">
      </div>

      <button type="submit" id="submit-btn" class="btn btn-primary">Thêm nhân viên</button>
      <button type="button" id="cancel-edit-btn" class="btn btn-secondary" style="display:none;">Hủy chỉnh sửa</button>
    </form>

    <div id="alerts"></div>

    <h4>Danh sách nhân viên</h4>
    <table class="table table-bordered" id="tbl-nhanvien">
      <thead>
        <tr>
          <th>Mã ND</th><th>Họ Tên</th><th>Chức Vụ</th><th>Lương</th><th>Vai Trò</th><th>Hành động</th>
        </tr>
      </thead>
      <tbody></tbody>
    </table>
  </div>

<script>
/* Thiết lập CSRF token cho AJAX */
$.ajaxSetup({
  headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
});

function showAlert(msg, type='danger') {
  $('#alerts').html('<div class="alert alert-'+type+'">'+msg+'</div>');
  setTimeout(()=>$('#alerts').html(''), 4000);
}

/* Load danh sách nhân viên từ endpoint /admin/nhanvien/list */
function loadNhanVien() {
  $.get('/admin/nhanvien/list', function(res){
    if(res && res.success) {
      const tbody = $('#tbl-nhanvien tbody').empty();
      res.data.forEach(nv => {
        const ten = nv.nguoi_dung ? (nv.nguoi_dung.HoTen || '') : '';
        const row = `<tr>
          <td>${nv.MaNguoiDung}</td>
          <td>${ten}</td>
          <td>${nv.ChucVu || ''}</td>
          <td>${nv.Luong ?? ''}</td>
          <td>${nv.VaiTro || ''}</td>
          <td>
            <button class="btn btn-sm btn-info edit-btn" data-id="${nv.MaNguoiDung}">Sửa</button>
            <button class="btn btn-sm btn-danger delete-btn" data-id="${nv.MaNguoiDung}">Xóa</button>
          </td>
        </tr>`;
        tbody.append(row);
      });
    } else {
      showAlert('Không tải được danh sách nhân viên', 'warning');
    }
  }).fail(function(){
    showAlert('Lỗi khi gọi server để lấy danh sách', 'danger');
  });
}

$(function(){
  loadNhanVien();

  // Thêm hoặc cập nhật
  $('#nhanvien-form').on('submit', function(e){
    e.preventDefault();
    const id = $('#MaNguoiDung').val().trim();
    if (!id) { showAlert('Mã người dùng bắt buộc', 'warning'); return; }

    const payload = {
      MaNguoiDung: id,
      ChucVu: $('#ChucVu').val(),
      Luong: $('#Luong').val(),
      VaiTro: $('#VaiTro').val()
    };

    const isEdit = $('#submit-btn').data('mode') === 'edit';

    if (!isEdit) {
      // create -> POST /admin/nhanvien
      $.post('/admin/nhanvien', payload)
        .done(function(res){
          if(res.success) {
            showAlert('Thêm thành công','success');
            $('#nhanvien-form')[0].reset();
            loadNhanVien();
          } else {
            showAlert(res.message || 'Có lỗi khi thêm','danger');
          }
        })
        .fail(function(xhr){
          if(xhr.status===422 && xhr.responseJSON && xhr.responseJSON.errors) {
            showAlert(Object.values(xhr.responseJSON.errors).flat().join('\\n'), 'warning');
          } else {
            showAlert('Lỗi server khi thêm', 'danger');
          }
        });
    } else {
      // update -> POST with _method=PUT to /admin/nhanvien/{id}
      $.ajax({
        url: '/admin/nhanvien/' + encodeURIComponent(id),
        method: 'POST',
        data: Object.assign({}, payload, {'_method':'PUT'}),
        success: function(res){
          if(res.success) {
            showAlert('Cập nhật thành công','success');
            $('#nhanvien-form')[0].reset();
            $('#submit-btn').text('Thêm nhân viên').removeData('mode');
            $('#MaNguoiDung').prop('readonly', false);
            $('#cancel-edit-btn').hide();
            loadNhanVien();
          } else {
            showAlert(res.message || 'Lỗi khi cập nhật','danger');
          }
        },
        error: function(xhr){
          if(xhr.status===422 && xhr.responseJSON && xhr.responseJSON.errors) {
            showAlert(Object.values(xhr.responseJSON.errors).flat().join('\\n'), 'warning');
          } else {
            showAlert('Lỗi server khi cập nhật', 'danger');
          }
        }
      });
    }
  });

  // Hủy edit
  $('#cancel-edit-btn').on('click', function(){
    $('#nhanvien-form')[0].reset();
    $('#submit-btn').text('Thêm nhân viên').removeData('mode');
    $('#MaNguoiDung').prop('readonly', false);
    $(this).hide();
  });

  // Sửa: lấy dữ liệu từ danh sách đã load (không gọi API riêng), gán vào form
  $(document).on('click', '.edit-btn', function(){
    const id = $(this).data('id');
    // Lấy danh sách hiện có từ server để đảm bảo dữ liệu mới nhất
    $.get('/admin/nhanvien/list', function(res){
      if(res && res.success) {
        const nv = res.data.find(x => String(x.MaNguoiDung) === String(id));
        if(nv) {
          $('#MaNguoiDung').val(nv.MaNguoiDung).prop('readonly', true);
          $('#ChucVu').val(nv.ChucVu);
          $('#Luong').val(nv.Luong);
          $('#VaiTro').val(nv.VaiTro);
          $('#submit-btn').text('Cập nhật').data('mode','edit');
          $('#cancel-edit-btn').show();
        } else {
          showAlert('Không tìm thấy nhân viên để sửa', 'warning');
        }
      } else {
        showAlert('Lỗi tải dữ liệu để sửa', 'danger');
      }
    }).fail(function(){
      showAlert('Lỗi khi gọi server để lấy dữ liệu sửa', 'danger');
    });
  });

  // Xóa
  $(document).on('click', '.delete-btn', function(){
    const id = $(this).data('id');
    if (!confirm('Bạn có muốn xóa nhân viên ' + id + '?')) return;
    // DELETE to /admin/nhanvien/{id} (sử dụng method spoofing với POST nếu cần)
    $.ajax({
      url: '/admin/nhanvien/' + encodeURIComponent(id),
      method: 'POST',
      data: {'_method':'DELETE'},
      success: function(res){
        if(res.success) {
          showAlert('Đã xóa','success');
          loadNhanVien();
        } else {
          showAlert(res.message || 'Lỗi khi xóa', 'danger');
        }
      },
      error: function(){
        showAlert('Lỗi server khi xóa', 'danger');
      }
    });
  });
});
</script>
</body>
</html>
