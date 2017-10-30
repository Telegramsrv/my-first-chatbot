<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171017031806 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('
                        INSERT INTO category (id, description, created_at, updated_at, is_enabled) VALUES
                        (1, \'Doces\', \'2017-10-16 23:52:29\', \'2017-10-16 23:52:29\', 1),
                        (2, \'Especial\', \'2017-10-16 23:52:29\', \'2017-10-16 23:52:29\', 1),
                        (3, \'Tradicional\', \'2017-10-16 23:52:29\', \'2017-10-16 23:52:29\', 1);
                        INSERT INTO pizza (id, category_id, description, subtitle, price, image, detail, code, url_detail, created_at, updated_at, is_enabled) VALUES
                        (1, 3, \'Calabresa - Pizza Tradicional\', \'Pizza de Calabresa - Tradicional\', 20.00, \'http://www.donapizzadf.com.br/images/stories/virtuemart/product/pizza.jpg\', NULL, \'1\', \'http://www.donapizzadf.com.br/index.php/cardapio/tradicional/calabresa\', \'2017-10-16 23:52:29\', \'2017-10-16 23:52:29\', 1),
                        (2, 3, \'Romeu e Julieta - Pizza Tradicional\', \'Romeu e Julieta - Tradicional\', 25.00, \'http://www.donapizzadf.com.br/images/stories/virtuemart/product/pizza_doce.jpg\', NULL, \'5\', \'http://www.donapizzadf.com.br/index.php/cardapio/tradicional/romeu-e-julieta\', \'2017-10-16 23:52:29\', \'2017-10-16 23:52:29\', 1),
                        (3, 1, \'Queijo Coalho com Rapadura - Pizza Doce\', \'Queijo Coalho com Rapadura - Pizza Doce\', 42.00, \'http://www.donapizzadf.com.br/images/stories/virtuemart/product/pizza_queijo_coalho.jpg\', NULL, \'50\', \'http://www.donapizzadf.com.br/index.php/cardapio/tradicional/romeu-e-julieta\', \'2017-10-16 23:52:29\', \'2017-10-16 23:52:29\', 1),
                        (4, 1, \'Morango com Nutella\', \'Pizza Morango com Nutella\', 35.00, \'http://www.donapizzadf.com.br/images/stories/virtuemart/product/pizza_morango_nutella.jpg\', NULL, \'52\', \'http://www.donapizzadf.com.br/index.php/melhores/morando-com-nutella\', \'2017-10-16 23:52:29\', \'2017-10-16 23:52:29\', 1),
                        (5, 2, \'Califórnia\', \'Pizza Califórnia\', 37.00, \'http://www.donapizzadf.com.br/images/stories/virtuemart/product/pizza_california.jpg\', NULL, \'51\', \'http://www.donapizzadf.com.br/index.php/cardapio/especial/51/pizza-california-detail\', \'2017-10-16 23:52:29\', \'2017-10-16 23:52:29\', 1),
                        (6, 2, \'Paulista - Pizza Especial\', \'Paulista - Pizza Especial\', 32.00, \'http://www.donapizzadf.com.br/images/stories/virtuemart/product/pizza.jpg\', NULL, \'14\', \'http://www.donapizzadf.com.br/index.php/cardapio/tradicional/2013-11-29-13-08-50/quatro-estacoes\', \'2017-10-16 23:52:29\', \'2017-10-16 23:52:29\', 1);
                        INSERT INTO uf (nome, sigla, regiao) VALUES (\'Acre\', \'AC\', \'Norte\');
                        INSERT INTO uf (nome, sigla, regiao) VALUES (\'Alagoas\', \'AL\', \'Nordeste\');
                        INSERT INTO uf (nome, sigla, regiao) VALUES (\'Amapá\', \'AP\', \'Norte\');
                        INSERT INTO uf (nome, sigla, regiao) VALUES (\'Amazonas\', \'AM\', \'Norte\');
                        INSERT INTO uf (nome, sigla, regiao) VALUES (\'Bahia\', \'BA\', \'Nordeste\');
                        INSERT INTO uf (nome, sigla, regiao) VALUES (\'Ceará\', \'CE\', \'Nordeste\');
                        INSERT INTO uf (nome, sigla, regiao) VALUES (\'Distrito Federal\', \'DF\', \'Centro-Oeste\');
                        INSERT INTO uf (nome, sigla, regiao) VALUES (\'Espírito Santo\', \'ES\', \'Sudeste\');
                        INSERT INTO uf (nome, sigla, regiao) VALUES (\'Goiás\', \'GO\', \'Centro-Oeste\');
                        INSERT INTO uf (nome, sigla, regiao) VALUES (\'Maranhão\', \'MA\', \'Nordeste\');
                        INSERT INTO uf (nome, sigla, regiao) VALUES (\'Mato Grosso\', \'MT\', \'Centro-Oeste\');
                        INSERT INTO uf (nome, sigla, regiao) VALUES (\'Mato Grosso do Sul\', \'MS\', \'Centro-Oeste\');
                        INSERT INTO uf (nome, sigla, regiao) VALUES (\'Minas Gerais\', \'MG\', \'Sudeste\');
                        INSERT INTO uf (nome, sigla, regiao) VALUES (\'Pará\', \'PA\', \'Nordeste\');
                        INSERT INTO uf (nome, sigla, regiao) VALUES (\'Paraíba\', \'PB\', \'Nordeste\');
                        INSERT INTO uf (nome, sigla, regiao) VALUES (\'Paraná\', \'PR\', \'Sul\');
                        INSERT INTO uf (nome, sigla, regiao) VALUES (\'Pernambuco\', \'PE\', \'Nordeste\');
                        INSERT INTO uf (nome, sigla, regiao) VALUES (\'Piauí\', \'PI\', \'Nordeste\');
                        INSERT INTO uf (nome, sigla, regiao) VALUES (\'Rio de Janeiro\', \'RJ\', \'Sudeste\');
                        INSERT INTO uf (nome, sigla, regiao) VALUES (\'Rio Grande do Norte\', \'R\', \'Nordeste\');
                        INSERT INTO uf (nome, sigla, regiao) VALUES (\'Rio Grande do Sul\', \'RS\', \'Sul\');
                        INSERT INTO uf (nome, sigla, regiao) VALUES (\'Rondônia\', \'RO\', \'Norte\');
                        INSERT INTO uf (nome, sigla, regiao) VALUES (\'Roraima\', \'RR\', \'Norte\');
                        INSERT INTO uf (nome, sigla, regiao) VALUES (\'Santa Catarina\', \'SC\', \'Sul\');
                        INSERT INTO uf (nome, sigla, regiao) VALUES (\'São Paulo\', \'SP\', \'Sudeste\');
                        INSERT INTO uf (nome, sigla, regiao) VALUES (\'Sergipe\', \'SE\', \'Nordeste\');
                        INSERT INTO uf (nome, sigla, regiao) VALUES (\'Tocantins\', \'TO\', \'Norte\');');

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
