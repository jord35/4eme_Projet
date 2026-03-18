
<?php



class Message extends AbstractEntity
{
    private string $title = "";
    private string $content = "";
    private ?DateTime $dateCreation = null;
    private ?DateTime $dateUpdate = null;



    //_______________________________________________
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getTitle(): string
    {
        return $this->title;
    }
    //_______________________________________________
    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function getContent(): string
    {
        return $this->content;
    }
    //_______________________________________________
    public function setDateCreation(string|DateTime|null $dateCreation): void
    {
        if ($dateCreation instanceof DateTime) {
            $this->dateCreation = $dateCreation;
            return;
        }

        $this->dateCreation = $dateCreation ? new DateTime($dateCreation) : null;
    }

    public function getDateCreation(): ?DateTime
    {
        return $this->dateCreation;
    }
    //_______________________________________________
    public function setDateUpdate(string|DateTime|null $dateUpdate): void
    {
        if ($dateUpdate instanceof DateTime) {
            $this->dateUpdate = $dateUpdate;
            return;
        }

        $this->dateUpdate = $dateUpdate ? new DateTime($dateUpdate) : null;
    }

    public function getDateUpdate(): ?DateTime
    {
        return $this->dateUpdate;
    }
    //_______________________________________________
}
