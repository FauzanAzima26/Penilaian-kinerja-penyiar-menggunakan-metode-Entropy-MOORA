<!-- Modal Tambah Kriteria -->
<div class="modal fade" id="modalKriteria" tabindex="-1" aria-labelledby="modalKriteriaLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="form-kriteria" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalKriteriaLabel">Tambah Kriteria</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label for="nama" class="form-label">Nama Kriteria</label>
          <input type="text" class="form-control" id="nama" name="nama" required>
        </div>
        <div class="mb-3">
          <label for="tipe" class="form-label">Tipe</label>
          <select class="form-select" id="tipe" name="tipe" required>
            <option value="">-- Pilih Tipe --</option>
            <option value="benefit">Benefit</option>
            <option value="cost">Cost</option>
          </select>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
        <button type="submit" class="btn btn-primary">Simpan</button>
      </div>
    </form>
  </div>
</div>
