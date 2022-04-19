<?php

namespace App\Factory;

use App\Entity\Fournisseur;
use App\Repository\FournisseurRepository;
use Zenstruck\Foundry\RepositoryProxy;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @extends ModelFactory<Fournisseur>
 *
 * @method static Fournisseur|Proxy createOne(array $attributes = [])
 * @method static Fournisseur[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Fournisseur|Proxy find(object|array|mixed $criteria)
 * @method static Fournisseur|Proxy findOrCreate(array $attributes)
 * @method static Fournisseur|Proxy first(string $sortedField = 'id')
 * @method static Fournisseur|Proxy last(string $sortedField = 'id')
 * @method static Fournisseur|Proxy random(array $attributes = [])
 * @method static Fournisseur|Proxy randomOrCreate(array $attributes = [])
 * @method static Fournisseur[]|Proxy[] all()
 * @method static Fournisseur[]|Proxy[] findBy(array $attributes)
 * @method static Fournisseur[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static Fournisseur[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static FournisseurRepository|RepositoryProxy repository()
 * @method Fournisseur|Proxy create(array|callable $attributes = [])
 */
final class FournisseurFactory extends ModelFactory
{
    public function __construct()
    {
        parent::__construct();

        // TODO inject services if required (https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services)
    }

    protected function getDefaults(): array
    {
        return [
            // TODO add your default values here (https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories)
            'nom' => self::faker()->text(),
            'adresse' => self::faker()->text(),
            'telephone' => self::faker()->text(),
        ];
    }

    protected function initialize(): self
    {
        // see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
        return $this
            // ->afterInstantiate(function(Fournisseur $fournisseur): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Fournisseur::class;
    }
}
