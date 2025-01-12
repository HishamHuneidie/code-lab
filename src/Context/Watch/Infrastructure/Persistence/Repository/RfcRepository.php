<?php

namespace Hisham\CodeLab\Context\Watch\Infrastructure\Persistence\Repository;

use Hisham\CodeLab\Common\Repository\AbstractWatchScrapRepository;
use Hisham\CodeLab\Context\Watch\Domain\Entity\PathnameVo;
use Hisham\CodeLab\Context\Watch\Domain\Entity\Rfc;
use Hisham\CodeLab\Context\Watch\Domain\Repository\RfcRepositoryInterface;
use Symfony\Component\DomCrawler\Crawler;

class RfcRepository extends AbstractWatchScrapRepository implements RfcRepositoryInterface
{

    /**
     * @inheritDoc
     */
    public function find(): array
    {
        $crawler = $this->fetch('rfcs');

        $rfcList = [];

        $articles = $crawler->filter('main article .content.rfc .rfc-info');
        $articles->each(function (Crawler $article) use (&$rfcList) {
            $titleNode = $article->filter('.title a');
            $pathname = $titleNode->attr('href');
            $title = trim($titleNode->text());

            $type = trim($article->filter('[class*="tag--change-type--"] .tag:nth-child(2)')->text());

            $versions = $article->filter('.tag--version .tag');
            $version = match ($versions->count()) {
                2       => trim($versions->eq(1)->text()),
                default => '',
            };

            $statuses = $article->filter('.rfc-status');
            $status = match ($statuses->count()) {
                0       => '',
                1       => trim($statuses->text()),
                default => trim($statuses->eq(0)->text()) . ' [' . trim($statuses->eq(1)->text()) . ']',
            };

            $rfcList[] = new Rfc(
                pathname: PathnameVo::create($pathname),
                title   : $title,
                type    : $type,
                version : $version,
                status  : $status,
            );
        });

        return $rfcList;
    }

    /**
     * @inheritDoc
     */
    public function findByPathname(PathnameVo $pathnameVo): Rfc
    {
        $crawler = $this->fetch($pathnameVo->getValue());

        $rows = $crawler->filter('.rfc-info tr');

        $title = $this->searchCellContent($rows, 'title');
        $type = $this->searchCellContent($rows, 'type');
        $version = $this->searchCellContent($rows, 'version');
        $status = $this->searchCellContent($rows, 'status');
        $phpLink = $this->searchPhpLink($rows);

        return new Rfc(
            pathname: $pathnameVo,
            title   : $title,
            type    : $type,
            version : $version,
            status  : $status,
            phpLink : $phpLink,
        );
    }

    public function searchCellContent(Crawler $rows, string $needle): string
    {
        foreach ($rows->getIterator() as $rowDocument) {
            $row = new Crawler($rowDocument);
            $cells = $row->filter('td');

            $cell1Content = strtolower($cells->eq(0)->text());
            $needle = strtolower($needle);

            if (str_contains($cell1Content, $needle)) {
                return trim($cells->eq(1)->text());
            }
        }

        return '';
    }

    public function searchPhpLink(Crawler $rows): string
    {
        foreach ($rows->getIterator() as $rowDocument) {
            $row = new Crawler($rowDocument);
            $cells = $row->filter('td');

            $cell1Content = strtolower($cells->eq(0)->text());
            $needle = 'title';

            if (str_contains($cell1Content, $needle)) {
                return trim($cells->eq(1)->filter('a')->attr('href'));
            }
        }

        return '';
    }

}
