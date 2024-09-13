<table style="width: 100%;">
    <tbody style="font-weight: 500; font-size: 20px; line-height: 1.25; text-align: center;">
        <tr>
            <td
                style="font-size: 24px; font-weight: 700; color: #fff; background-color: #8b75c8; padding: 30px 30px 30px 30px; margin-bottom: 20px;">
                <h1>
                    <?= $gt_title ?>
                </h1>
            </td>
        </tr>
        <tr>
            <td>
                <h2 style="font-size: 22px; margin-bottom: 20px; font-weight: 600;">
                    Приветствуем,
                    <?=$user->name ?>!
                </h2>
            </td>
        </tr>
        <?php foreach ($gt_contents as $trtd) : ?>
            <?php if (array_key_exists('style', $trtd) && is_string($trtd['style'])) : ?>
                <tr>
                    <td>
                        <div style="<?= $trtd['style'] ?>">
                            <?= $trtd['content'] ?>
                        </div>
                    </td>
                </tr>
            <?php elseif(array_key_exists('code-like', $trtd) && $trtd['code-like']): ?>
                <tr>
                    <td style="font-weight: 700; font-size: 30px;">
                        <div style="margin-top: 30px;">
                            <?= $trtd['content'] ?>
                        </div>
                    </td>
                </tr>
            <?php else: ?>
                <tr>
                    <td>
                        <?= $trtd['content'] ?>
                    </td>
                </tr>
            <?php endif ?>
        <?php endforeach ?>
    </tbody>
</table>