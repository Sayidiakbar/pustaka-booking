<table class="table mt-3">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Admin</th>
                        <th>Email</th>
                        <th>Role ID</th>
                        <th>Aktif</th>
                        <th>Admin Sejak</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $i = 1 ;
                        foreach ($admin as $d) { ?>
                            <tr>
                                <td><?= $i++; ?></td>
                                <td><?= $d['nama']; ?></td>
                                <td><?= $d['email']; ?></td>
                                <td><?= $d['role_id']; ?></td>
                                <td><?= $d['is_active']; ?></td>
                                <td><?= date('Y', $d['tanggal_input']); ?></td>
                            </tr>
                    <?php } ?>
                </tbody>
            </table>
