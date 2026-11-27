<?php

class EntryController extends Controller
{
    public function index(): void
    {
        $this->requireLogin();
        $this->render('saved', [
            'title'   => 'saved',
            'entries' => Entry::allFor($this->userId()),
        ]);
    }

    public function store(): void
    {
        $this->requireLogin();

        $action = (string) ($_POST['action'] ?? '');
        $format = (string) ($_POST['format'] ?? '');
        $input  = (string) ($_POST['input'] ?? '');
        $output = (string) ($_POST['output'] ?? '');

        if (!in_array($action, ['encode', 'decode', 'find'], true) || !isset(Codec::formats()[$format])) {
            $this->flash('could not save that >.<');
            $this->redirect('/tools');
        }

        Entry::create($this->userId(), $action, $format, $input, $output);
        $this->flash('successfully saved!');
        $this->redirect('/saved');
    }

    public function destroy(): void
    {
        $this->requireLogin();
        Entry::delete((int) ($_POST['id'] ?? 0), $this->userId());
        $this->flash('deleted, it is gone forever');
        $this->redirect('/saved');
    }
}
