nanti folder perpustakaan ini jangan lupa dipindah keluar dari folder editdashboard, oke? OKE! oleh mshofadev

$sql_kategori = "SELECT * FROM kategori_buku";
$result_kategori = $db->query($sql_kategori);

     <?php while ($data_kategori = $result_kategori->fetch_assoc()) { ?>
                                                        <p>
                                                            Kategori Buku :
                                                            <?= $data_kategori['nama_kategori'] ?>
                                                        </p>
                                                    <?php } ?>

punya gw yg lama

  <tbody>
                                <?php while ($data = $result->fetch_assoc()) { ?>
                                    <tr>
                                        <td>
                                            <?= $data['buku_id'] ?>
                                        </td>
                                        <td>
                                            <?= $data['judul'] ?>
                                        </td>
                                        <td>
                                            <?= $data['penulis'] ?>
                                        </td>
                                        <td>
                                            <?= $data['penerbit'] ?>
                                        </td>
                                        <td>
                                            <?= $data['tahun_terbit'] ?>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-primary" data-toggle="modal"
                                                data-target="#modal-default">
                                                Lihat Buku
                                            </button>
                                        </td>
                                    </tr>
                                    <!-- modal -->
                                    <div class="modal fade" id="modal-default">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title">Lihat Buku</h4>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <!-- <img src="../dist/img/buku-1.png" alt="" width="400px"> -->
                                                    <h5>
                                                        <?= $data['judul'] ?>
                                                    </h5>
                                                    <p>
                                                        <?= $data['penulis'] ?>
                                                    </p>
                                                </div>
                                                <div class="modal-footer justify-content-between">
                                                    <button type="button" class="btn btn-primary">Pinjam Buku</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- modal end -->
                                <?php } ?>

                            </tbody>
