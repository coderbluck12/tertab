<?php

namespace App\Services;

class SeoService
{
    protected $title;
    protected $description;
    protected $keywords;
    protected $ogImage;
    protected $canonical;
    protected $robots;

    public function __construct()
    {
        $this->title = config('app.name', 'Tertab') . ' - Best Reference Platform';
        $this->description = 'Get verified academic and professional references from trusted lecturers. Secure, fast, and reliable reference platform for students and professionals.';
        $this->keywords = 'references, academic references, word refernces, professional references, verified lecturers, student references, academic recommendation letters, university references, college references';
        $this->ogImage = asset('images/og-image.jpg');
        $this->canonical = request()->url();
        $this->robots = 'index, follow';
    }

    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    public function setKeywords($keywords)
    {
        $this->keywords = $keywords;
        return $this;
    }

    public function setOgImage($ogImage)
    {
        $this->ogImage = $ogImage;
        return $this;
    }

    public function setCanonical($canonical)
    {
        $this->canonical = $canonical;
        return $this;
    }

    public function setRobots($robots)
    {
        $this->robots = $robots;
        return $this;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getKeywords()
    {
        return $this->keywords;
    }

    public function getOgImage()
    {
        return $this->ogImage;
    }

    public function getCanonical()
    {
        return $this->canonical;
    }

    public function getRobots()
    {
        return $this->robots;
    }

    public function generateMetaTags()
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
            'keywords' => $this->keywords,
            'og_image' => $this->ogImage,
            'canonical' => $this->canonical,
            'robots' => $this->robots,
        ];
    }
}
