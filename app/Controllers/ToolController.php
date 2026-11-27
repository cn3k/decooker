<?php

class ToolController extends Controller
{
    public function home(): void
    {
        $this->redirect('/tools');
    }

    public function index(): void
    {
        $this->page();
    }

    public function convert(): void
    {
        $input  = (string) ($_POST['input'] ?? '');
        $action = (string) ($_POST['action'] ?? 'encode');
        $format = (string) ($_POST['format'] ?? 'base64');
        $result = null;
        $error  = null;

        if (trim($input) === '') {
            $error = 'type something first, i can\'t cook air >.<';
        } elseif (strlen($input) > 100000) {
            $error = 'whoa, that is too much to cook at once (max 100000 chars) >.<';
        } elseif ($action === 'find') {
            if ($this->userId() === null) {
                $error = 'find mode needs an account, log in first >.<';
            } elseif ($found = Codec::detect($input)) {
                $result = ['action' => 'find', 'format' => $found[0], 'output' => $found[1]];
            } else {
                $error = 'could not detect the encoding >.<';
            }
        } elseif (!isset(Codec::formats()[$format])) {
            $error = 'idk that format >.<';
        } elseif ($action === 'encode') {
            $result = ['action' => 'encode', 'format' => $format, 'output' => Codec::encode($format, $input)];
        } elseif ($action === 'decode') {
            if (!Codec::canDecode($format)) {
                $error = 'hashes are one-way, you can\'t un-cook them >.<';
            } else {
                $output = Codec::decode($format, $input);
                if ($output === null) {
                    $error = 'that does not look like valid ' . Codec::label($format) . ' >.<';
                } else {
                    $result = ['action' => 'decode', 'format' => $format, 'output' => $output];
                }
            }
        } else {
            $error = 'idk that action >.<';
        }

        $this->page(compact('input', 'action', 'format', 'result', 'error'));
    }

    private function page(array $data = []): void
    {
        $this->render('tool', $data + [
            'title'   => 'tools',
            'input'   => '',
            'action'  => 'encode',
            'format'  => 'base64',
            'result'  => null,
            'error'   => null,
            'formats' => Codec::formats(),
        ]);
    }
}
