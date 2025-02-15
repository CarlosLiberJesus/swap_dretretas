# swap_dretretas

DreTretas é um heroi! Projecto dedicado a eles...

Eles já tẽm [site top](https://dre.tretas.org/), e disponibilizaram os seus crawllers e um dump da sua BD.

Nós estamos neste projecto:

1. A rever o dump, tentar encontrar padrões de estudo, e dados em falta
2. A criar um processo de swap para uma BD relacional que vai encontrar mais chaves que só entrei leis
3. Adicionar middleware de repopulamento de objectos em falta
4. Insersão em BD Real de dados tratados
5. Rever o processo de importação diario e recomeçar do ponto 1.
6. Partilhar open-source todos os dados

## Requirements

- composer v2.8.4
- PHP v8.4.1
- Composer v2.8.4
- python 3.12

### Python Scripts

A directoria do python vai ser a ultima fase, que deveria ser o que os herois [DreTretas](https://gitlab.com/hgg/dre/) tinham começado

A ideia é depois de estabilizar o load, é conseguir puxar o processo de importação diario deles, com o nosso processo de migração e populamento.

Estamos gratos por toda a inspiração à equipa do DRE-Tretas, pela inspiração e empenho anterior, e vou continuar o open-source mindset

```sh
cd python
python3 -m venv venv
source venv/bin/activate
```
