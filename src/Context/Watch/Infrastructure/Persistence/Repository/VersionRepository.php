<?php

namespace Hisham\CodeLab\Context\Watch\Infrastructure\Persistence\Repository;

use Hisham\CodeLab\Common\Repository\AbstractWatchScrapRepository;
use Hisham\CodeLab\Context\Watch\Domain\Entity\ReleaseVo;
use Hisham\CodeLab\Context\Watch\Domain\Entity\Version;
use Hisham\CodeLab\Context\Watch\Domain\Repository\VersionRepositoryInterface;
use Symfony\Component\DomCrawler\Crawler;

class VersionRepository
    extends AbstractWatchScrapRepository
    implements VersionRepositoryInterface
{

    /**
     * @inheritDoc
     */
    public function find(): array
    {
        $crawler = $this->fetch('versions');

        $versionList = [];

        $articles = $crawler->filter('main article .version-item');
        $articles->each(function (Crawler $article) use (&$versionList) {
            // Declare nodes
            $title = $article->filter('.title a');
            $versionNumber = trim($title->text());
            $link = $title->attr('href');
            $status = trim($article->filter('.tag--release-status .tag:nth-child(2)')->text());
            // Declare release details
            $releaseTags = $article->filter('.tagline .tag--releases-list .tag');

            $releaseVersionNumber = $releaseTags->count() >= 2
                ? trim($releaseTags->eq(1)->filter('a')->text())
                : null;

            $releaseDate = trim($article->filter('.tagline .tag--release-date .tag:nth-child(2)')->text());

            $releaseLink = $releaseTags->count() >= 2
                ? trim($releaseTags->eq(1)->filter('a')->attr('href'))
                : null;

            $releaseListLink = $releaseTags->count() >= 1
                ? trim($releaseTags->eq(0)->filter('a:nth-child(1)')->attr('href'))
                : null;

            // Pushing version
            $versionList[] = new Version(
                versionNumber: $versionNumber,
                link         : $link,
                status       : $status,
                release      : new ReleaseVo(
                    versionNumber: $releaseVersionNumber,
                    link         : $releaseLink,
                    date         : $releaseDate,
                    listLink     : $releaseListLink,
                ),
            );

        });

        return $versionList;
    }

    /**
     * @inheritDoc
     */
    public function findByVersionNumber(string $versionNumber): Version
    {
        // TODO: Implement findByVersionNumber() method.
    }

}
