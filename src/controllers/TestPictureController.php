<?php

require_once ROOT_DIR . 'src/controllers/common/AbstractController.php';
require_once ROOT_DIR . 'src/controllers/common/helper/PictureHelper.php';

class TestPictureController extends AbstractController
{
    private PictureHelper $pictureHelper;

    public function __construct()
    {
        $this->pictureHelper = new PictureHelper();
    }

    public function index(): void
    {
        $viewData = [
            'message' => null,
            'error' => null,
            'picturePackage' => null,
            'uploadedTitle' => null,
            'uploadedComment' => null
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = trim($_POST['title'] ?? '');
            $comment = trim($_POST['comment'] ?? '');

            if (empty($_FILES['picture'])) {
                $viewData['error'] = 'Aucune image envoyée.';
            } else {
                $saveResponse = $this->pictureHelper->savePicture($_FILES['picture'], [
                    'title' => $title,
                    'alt_text' => $title,
                    'variant_type' => 'book_detail'
                ]);

                if (!$saveResponse['success']) {
                    $viewData['error'] = $saveResponse['error'];
                } else {
                    $pictureId = $saveResponse['data']['picture_id'] ?? null;

                    if ($pictureId) {
                        $packageResponse = $this->pictureHelper->getPicturePackage($pictureId, 'book_detail');

                        if ($packageResponse['success']) {
                            $viewData['picturePackage'] = $packageResponse['data'];
                            $viewData['uploadedTitle'] = $title;
                            $viewData['uploadedComment'] = $comment;
                            $viewData['message'] = 'Image enregistrée avec succès.';
                        } else {
                            $viewData['error'] = $packageResponse['error'];
                        }
                    } else {
                        $viewData['error'] = 'Impossible de récupérer l’image enregistrée.';
                    }
                }
            }
        }

        extract($viewData);
        require ROOT_DIR . 'src/views/templates/test-picture.php';
    }
}