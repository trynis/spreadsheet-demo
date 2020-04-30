<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Product
 *
 * @ORM\Entity()
 */
class Product
{
    const HEADERS = [
        'ID',
        'title',
        'description',
        'summary',
        'gtin',
        'mpn',
        'price',
        'shortcode',
        'category',
        'sub',
        'date',
    ];

    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     * @var int
     */
    protected $baseId;

    /**
     * @ORM\Column(type="integer")
     * @var int
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    protected $title;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    protected $description;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    protected $summary;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    protected $gtin;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    protected $mpn;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    protected $price;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    protected $shortcode;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    protected $category;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    protected $sub;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    protected $date;

    /**
     * @return int
     */
    public function getBaseId(): int
    {
        return $this->baseId;
    }

    /**
     * @param int $baseId
     */
    public function setBaseId(int $baseId): void
    {
        $this->baseId = $baseId;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getSummary(): string
    {
        return $this->summary;
    }

    /**
     * @param string $summary
     */
    public function setSummary(string $summary): void
    {
        $this->summary = $summary;
    }

    /**
     * @return string
     */
    public function getGtin(): string
    {
        return $this->gtin;
    }

    /**
     * @param string $gtin
     */
    public function setGtin(string $gtin): void
    {
        $this->gtin = $gtin;
    }

    /**
     * @return string
     */
    public function getMpn(): string
    {
        return $this->mpn;
    }

    /**
     * @param string $mpn
     */
    public function setMpn(string $mpn): void
    {
        $this->mpn = $mpn;
    }

    /**
     * @return string
     */
    public function getPrice(): string
    {
        return $this->price;
    }

    /**
     * @param string $price
     */
    public function setPrice(string $price): void
    {
        $this->price = $price;
    }

    /**
     * @return string
     */
    public function getShortcode(): string
    {
        return $this->shortcode;
    }

    /**
     * @param string $shortcode
     */
    public function setShortcode(string $shortcode): void
    {
        $this->shortcode = $shortcode;
    }

    /**
     * @return string
     */
    public function getCategory(): string
    {
        return $this->category;
    }

    /**
     * @param string $category
     */
    public function setCategory(string $category): void
    {
        $this->category = $category;
    }

    /**
     * @return string
     */
    public function getSub(): string
    {
        return $this->sub;
    }

    public function getSubArray(): array
    {
        $array = unserialize($this->getSub());

        if (!is_array($array)) {
            return [];
        }

        return $array;
    }

    public function getSubArrayString(): string
    {
        $subs = $this->getSubArray();

        array_walk($subs, function(&$item) {
            $item = http_build_query($item, '', ', ');
        });

        return implode(', ', $subs);
    }

    /**
     * @param string|array $sub
     */
    public function setSub($sub): void
    {
        if (is_array($sub)) {
            $sub = serialize($sub);
        }

        $this->sub = $sub;
    }

    /**
     * @return string
     */
    public function getDate(): string
    {
        return $this->date;
    }

    /**
     * @param string $date
     */
    public function setDate(string $date): void
    {
        $this->date = $date;
    }

    /**
     * @param array $array
     */
    public function fromArray(array $array)
    {
        foreach ($array as $key => $value) {
            $property = strtolower($key);
            $method = sprintf('set%s', ucfirst($property));
            if (method_exists($this, $method)) {
                $this->$method($value);
            }
        }
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'ID' => $this->getId(),
            'title' => $this->getTitle(),
            'description' => $this->getDescription(),
            'summary' => $this->getSummary(),
            'gtin' => $this->getGtin(),
            'mpn' => $this->getMpn(),
            'price' => $this->getPrice(),
            'shortcode' => $this->getShortcode(),
            'category' => $this->getCategory(),
            'sub' => $this->getSubArrayString(),
            'date' => $this->getDate(),
        ];
    }
}