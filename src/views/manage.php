<div class="container" id="esolaAppSocialLawyers">
    <div class="">
        <div class="">
            <button id="refreshSocial">Refrescar</button>
        </div>
        <div>
            <h3 id="titleform">Asignar nueva red social a abogado</h3>
            <button id="resetBtn" type="button">Resetear</button>
        </div>
        
        <form id="socialFormEsola" action="" method="POST" data-state="new">
            <label for="">
                ID <br/>
                <input type="number" name="id" id="" value="" disabled/>
            </label>
            <label for="">
                Idioma <br/>
                <input type="text" name="lang" value="<?= pll_current_language() != null ? pll_current_language() : 'es'?>" disabled/>
            </label>
            <label for="">
                Páginas de abogados <?php echo ($_GET['lang'] == 'en') ? ' (en)' : '(es)'; ?> <br />
                <select name="post_id" required>
                    <?php foreach ($i->posts as $post) : ?>
                        <option value="<?= $post->ID ?>" > <?= $post->post_title ?></option>
                    <?php endforeach; ?>
                </select>

            </label>
            
            <label for="">
                Red Social <br/>
                <select name="name" required>                    
                    <option value="Linkedin">Linkedin</option>
                    <!-- <option value="Facebook">Facebook</option>
                    <option value="Twitter">Twitter</option> -->
                </select>
            </label>

            <label>
                Enlace <br>
                <input required type="text" name="link" value="" placeholder="https://social.com/lawyer"/>
            </label>
            <button name="actionBtn" type="submit">Guardar</button>
        </form>
    </div>
    <table class="styled-table">
        <thead>
            <th>Id</th>
            <th>Social</th>
            <th>Lawyer</th>
            <th>Link</th>
            <th colspan="2" >Opciones</th>
        </thead>
        <tbody id="tbody-social">
            <?php if (sizeof($i->data) > 0) : ?>
                <?php foreach ($i->data as $social) : ?>
                    <tr>
                        <td><?= $social['id'] ?></td>
                        <td><?= $social['name'] ?></td>
                        <td><?= get_the_title($social['post_id']) ?></td>
                        <td><strong><a target="_blank" href="<?= $social['link'] ?>"><?= $social['link']?></a></strong></td>
                        <td><button name="editBtn" data-id="<?= $social['id'] ?>">Editar</button></td>
                        <td><button name="deleteBtn" data-id="<?= $social['id'] ?>">Eliminar</button></td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="4"><strong>No se encontraron registros</strong></td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>


</div>