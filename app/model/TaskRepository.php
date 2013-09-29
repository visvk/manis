<?php

namespace Project;

use Nette;



class TaskRepository extends Repository
{
    public function tasksOf(Nette\Database\Table\ActiveRow $list)
	{
		return $list->related('task')->order('created');
	}
    
    
    public function findAllTask(){
        return $this->findAll();
    }
    public function findByName($text)
{
    return $this->findAll()->where('text', $text)->fetch();
}
    public function findByID($task_id)
{
    return $this->findAll()->where('id', $task_id)->fetch();
}

    public function taskRegistration($text, $project,$solver,$created,$submitted,$numfiles)
{
    
   
        $this->getTable()->insert(array(
        'text' => $text,
        'project_id' => $project,
        'desc' => 'Pridajte popis úlohy pomocou editácie úlohy.',
        'user_subj_id' => $solver,
        'created' =>$created,
        'submitted' =>$submitted,
        'numfiles' => $numfiles,
         'grade' => 0
            ));
} 

public function taskEdit($id, $text,$created, $submitted, $popis,$numfiles)
{
    $this->findBy(array('id' => $id))->update(array(
        'text' => $text,       
        'desc' => $popis,
      //  'user_subj_id' => $solver,
       // 'created' => new \Nette\DateTime(),
        'created' =>$created,
        'numfiles' => $numfiles,
        'submitted' =>$submitted
         //'done' => 0
    )
    );
}
/**
	 * @param int $id
	 */
	public function markDone($id)
	{
		$this->findBy(array('id' => $id))->update(array('grade' => 100));
                
	}
 
}